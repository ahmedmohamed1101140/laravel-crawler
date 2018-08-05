<?php

namespace App\Http\Controllers;

use App\Product;
use Carbon\Carbon;
use DOMDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()){
            $products = Auth::user()->products;
            foreach ($products as $product){
                if($product->type == 'souq'){
                    $this->refresh_souq_items($product);
                }
            }
            Session::flash('success','All Products Syncs');
            return view('home');
        }
        return view('home');
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
