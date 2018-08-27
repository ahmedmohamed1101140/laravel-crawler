<?php

namespace App\Http\Controllers;

use App\Amount;
use App\Product;
use Carbon\Carbon;
use DOMDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;


class ProductController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function add_new (Request $request){
        $this->validate($request,array(
            'link' => 'required',
            'paymentMethod' => 'required'
        ));

        foreach (auth()->user()->products as $product){
            if($product->url == $request->link){
                Session::flash('warning',"You all ready have the product in your list!");
                return redirect()->back();
            }
        }


        if($request->paymentMethod == 'jumia'){
            Session::flash('error',"we Don't support them yet!");
            return redirect()->back();
        }

        else if($request->paymentMethod =='amazon' && str_contains($request->link,'amazon.com')){
            if(!$this->add_amazon($request)){
                Session::flash('error',"Failed To Find Amazon.com Product Please Try Again");
                return redirect()->back();
            }
        }

        else if($request->paymentMethod =='souq' && str_contains($request->link,'souq.com')){
            if(!$this->add_souq($request)){
                Session::flash('error',"Product Amount not found due to connection issues or Souq don't provide your product quantity.");
                return redirect()->back();
            }
        }
        else{
            Session::flash('error',"Link Dosen't Match with the Chosen website");
            return redirect()->back();
        }


        Session::flash('success','Product Added Successfully');
        return redirect()->back();
    }

    public function refresh(){
        $products = Auth::user()->products;
        if(count($products) == 0){
            Session::flash('warning',"You have no product in your list start add souq products");
            return redirect()->back();
        }
        foreach ($products as $product){
            if($product->type == 'souq'){
                $this->refresh_souq_items($product);
            }
            elseif($product->type == 'amazon'){
                $this->refresh_amazon_items($product);
            }
        }

        Session::flash('success','All Products Syncs');
        return redirect()->back();
    }

    protected function refresh_souq_items($item){
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
        $new_amount ='';
        foreach($items as $value)
        {
            if($value->getAttribute('class') == 'txtcolor-alert xleft' ){
                $attrs = $value->nodeValue;
//                $product->amount = $attrs;
                $new_amount = $attrs;
            }
        }
        if($new_amount != $product->amount){
            $product->amount = $new_amount;
            $amount = new Amount();
            $amount->prod_id = $product->id;
            $amount->amount = $new_amount;
            $amount->save();
        }

        $product->save();
        return;
    }

    protected function add_souq($request){
        $product = new Product();
        $product->user_id = auth()->user()->id;
        $product->last_update = Carbon::now();
        $product->url = $request->link;
        $product->type = $request->paymentMethod;



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

        if($product->name == "Not Found" || strlen($product->amount) < 10 || $product->price ==0){
            return false;
        }

        $product->save();
        $amount = new Amount();
        $amount->prod_id = $product->id;
        $amount->amount = $product->amount;
        $amount->save();
        return true;
    }

    public function delete($id){
        Product::destroy($id);
        Session::flash('success','Product Deleted Successfully');
        return redirect()->back();
    }

    public function refresh_ajax(Request $request){
        $products = Auth::user()->products;
        foreach ($products as $product){
            if($product->type == 'souq'){
                $this->refresh_souq_items($product);
            }
            elseif($product->type == 'amazon'){
                $this->refresh_amazon_items($product);
            }
        }
        return true;
    }

    protected function add_amazon($request){
        $product = new Product();
        $product->user_id = auth()->user()->id;
        $product->last_update = Carbon::now();
        $product->url = $request->link;
        $product->type = $request->paymentMethod;
        $product->link = $request->link;


        $main_url= $request->link;
        $str = file_get_contents($main_url);

        // Gets Webpage Title
        if(strlen($str)>0)
        {
            $str = trim(preg_replace('/\s+/', ' ', $str)); // supports line breaks inside <title>
            preg_match("/\<title\>(.*)\<\/title\>/i",$str,$title); // ignore case
        }


        // Gets Webpage Internal Links
        $doc = new DOMDocument;
        @$doc->loadHTML($str);


        $items = $doc->getElementsByTagName('span');
        foreach($items as $value)
        {
            if($value->getAttribute('class') == 'a-size-medium a-color-success' ){
                $attrs = $value->nodeValue;
                $product->amount = $attrs;
            }
        }



        $items = $doc->getElementsByTagName('span');
        foreach($items as $value)
        {
            if($value->getAttribute('class') == 'a-size-medium a-color-price' ){
                $attrs = $value->nodeValue;
                $product->price = $attrs;
            }
        }


        $items = $doc->getElementsByTagName('span');
        foreach($items as $value)
        {
            if($value->getAttribute('class') == 'a-size-large' ){
                $attrs = $value->nodeValue;
                $product->name = $attrs;

            }
        }
        if($product->name == "Not Found" && $product->amount == 'Not Found' && $product->price ==0){
            return false;
        }

        $product->save();
        return true;
    }

    protected function refresh_amazon_items($item){
        $product = Product::find($item->id);
        $product->last_update = Carbon::now();

        $main_url= $product->url;
        $str = file_get_contents($main_url);

        // Gets Webpage Title
        if(strlen($str)>0)
        {
            $str = trim(preg_replace('/\s+/', ' ', $str)); // supports line breaks inside <title>
            preg_match("/\<title\>(.*)\<\/title\>/i",$str,$title); // ignore case
        }


        // Gets Webpage Internal Links
        $doc = new DOMDocument;
        @$doc->loadHTML($str);

        $items = $doc->getElementsByTagName('span');
        foreach($items as $value)
        {
            if($value->getAttribute('class') == 'a-size-medium a-color-success' ){
                $attrs = $value->nodeValue;
                $product->amount = $attrs;
            }
        }



        $product->save();
        return true;
    }

}
