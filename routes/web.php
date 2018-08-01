<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::post('/add','ProductController@add_new');
Route::post('/refresh','ProductController@refresh');

Route::post('/test',function(\Illuminate\Http\Request $request){
  $main_url= $request->url;
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
  $sec_url[] = array();
  foreach($items as $value)
  {
      if($value->getAttribute('class') == 'txtcolor-alert xleft' ){
          $attrs = $value->nodeValue;
          array_push($sec_url,$attrs);
      }
  }
  return view('welcome',compact('sec_url'));
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
