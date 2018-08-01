<?php

namespace App\Http\Controllers;

use App\Product;
use Carbon\Carbon;
use DOMDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;


class ProductController extends Controller
{
    //

    public function add_new (Request $request){
//        dd($request->all());
        $this->validate($request,array(
            'link' => 'required',
            'paymentMethod' => 'required'
        ));

        if($request->paymentMethod =='amazon' || $request->paymentMethod == 'jumia'){
            Session::flash('error',"we Don't support them yet!");
            return redirect()->back();
        }

        $product = new Product();
        $product->user_id = auth()->user()->id;
        $product->last_update = Carbon::now();
        $product->url = $request->link;


        $main_url= $request->link;
        $str = file_get_contents($main_url);
        // Gets Webpage Title
        if(strlen($str)>0)
        {
            $str = trim(preg_replace('/\s+/', ' ', $str)); // supports line breaks inside <title>
            preg_match("/\<title\>(.*)\<\/title\>/i",$str,$title); // ignore case
            $title=$title[1];
        }


        // Gets Webpage Internal Links
        $doc = new DOMDocument;
        @$doc->loadHTML($str);

        $items = $doc->getElementsByTagName('meta');
        foreach($items as $value)
        {
            if($value->getAttribute('property') == 'og:url' ){
                $attrs = $value->getAttribute('content');
                $product->link = $attrs;
            }
        }


        $items = $doc->getElementsByTagName('b');
        foreach($items as $value)
        {
            if($value->getAttribute('class') == 'txtcolor-alert xleft' ){
                $attrs = $value->nodeValue;
                $product->amount = $attrs;
            }
        }


        $items = $doc->getElementsByTagName('h3');
        foreach($items as $value)
        {
            if($value->getAttribute('class') == 'price is sk-clr1' ){
                $attrs = $value->nodeValue;
                $product->price = $attrs;
            }
        }


        $items = $doc->getElementsByTagName('div');
        foreach($items as $value)
        {
            if($value->getAttribute('class') == 'title' ){
                $attrs = $value->nodeValue;
                $product->name = $attrs;

            }
        }

        $product->save();
        Session::flash('success','Product Added Successfully');
        return redirect()->back();
    }

    public function refresh(Request $request){
        $products = Auth::user()->products;
        foreach ($products as $product){
            $this->refresh_items($product);
        }

        Session::flash('success','All Products Syncs');
        return redirect()->back();
    }

    protected function refresh_items($item){
        $product = Product::find($item->id);
        $product->last_update = Carbon::now();

        $main_url= $product->url;
        $str = file_get_contents($main_url);

        // Gets Webpage Title
        if(strlen($str)>0)
        {
            $str = trim(preg_replace('/\s+/', ' ', $str)); // supports line breaks inside <title>
            preg_match("/\<title\>(.*)\<\/title\>/i",$str,$title); // ignore case
            $title=$title[1];
        }


        // Gets Webpage Internal Links
        $doc = new DOMDocument;
        @$doc->loadHTML($str);

        $items = $doc->getElementsByTagName('b');
        foreach($items as $value)
        {
            if($value->getAttribute('class') == 'txtcolor-alert xleft' ){
                $attrs = $value->nodeValue;
                $product->amount = $attrs;
            }
        }


        $product->save();
        return;
    }
}
