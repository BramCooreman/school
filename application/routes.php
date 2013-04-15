<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Simply tell Laravel the HTTP verbs and URIs it should respond to. It is a
| breeze to setup your application using Laravel's RESTful routing and it
| is perfectly suited for building large applications and simple APIs.
|
| Let's respond to a simple GET request to http://example.com/hello:
|
|		Route::get('hello', function()
|		{
|			return 'Hello World!';
|		});
|
| You can even respond to more than one URI:
|
|		Route::post(array('hello', 'world'), function()
|		{
|			return 'Hello World!';
|		});
|
| It's easy to allow URI wildcards using (:num) or (:any):
|
|		Route::put('hello/(:any)', function($name)
|		{
|			return "Welcome, $name.";
|		});
|
*/

      /*
Route::any('home', function()
{
    return View::make('home.index');
});     */


Route::get('uusiMaksu', function() {
    return Route::controller('uusiMaksu.index');
});

//Route::controller('uusiMaksu');
//Route::controller(Controller::detect());
//Route::get('about', 'home@about');
Route::controller('home');
/*Route::get('/', function()
{
	return 
});    
Route::get('/', function()
{
	return View::make('login.index');
});*/
/*
|--------------------------------------------------------------------------
| Application 404 & 500 Error Handlers
|--------------------------------------------------------------------------
|
| To centralize and simplify 404 handling, Laravel uses an awesome event
| system to retrieve the response. Feel free to modify this function to
| your tastes and the needs of your application.
|
| Similarly, we use an event to handle the display of 500 level errors
| within the application. These errors are fired when there is an
| uncaught exception thrown in the application. The exception object
| that is captured during execution is then passed to the 500 listener.
|
*/
/*
Route::get('login', function() {
    return Route::controller('login');
});*/

Route::get('lang/(:any)', function($language) {
    Cookie::put('lang', $language, time() + 60*60*24*30);
    return Redirect::to('home');
    
});

Route::post('lang/(:any)', function($language) {
    Cookie::put('lang', $language, time() + 60*60*24*30);
    return Route::controller('home');
});

Route::post('hyvaksyMaksu', function() {
    return View::make('hyvaksyMaksu.index');
});

Route::post('hyvaksySiirto', function() {
    return View::make('hyvaksySiirto.index');
});

Route::post('haeViiteaineisto', function() {
    return View::make('saapuvatViitemaksut.index');
});

Route::post('haeTiliote', function() {
    return View::make('konekielinenTiliote.index');
});

Route::post('vaihdaRooli', function() {
    return View::make('roolinVaihto.index');
});

Route::get('sivu', function() {
    if(!Session::has('Authenticated'))
    {
        $language = 'fin';
        Session::put('lang', $language);
        return Route::controller('home');
    }
});

Route::get('sivu/(:any)', function($sivu) {
   return Redirect::to('home')->with('sivu',$sivu);
});
/*
Route::post('login', function() {
      $userdata = array(
        'username'      => Input::get('username'),
        'password'      => Input::get('password')
    );
   
});*/
Route::post('login', function() {
       // get POST data
    $userdata = array(
        'username'      => mysql_real_escape_string(Input::get('username')),
        'password'      => mysql_real_escape_string(Input::get('password'))
    );
       //echo $userdata['password'];
   if ( Auth::attempt($userdata) )
    {
        // we are now logged in, go to home
        return Redirect::to('home');
    }
    else
    {
        // auth failure! lets go back to the login
        return Redirect::to('login')
            ->with('login_errors', true);
        // pass any error notification you want
        // i like to do it this way :)
    }
});
/*
Route::get('home', array('before' => 'filter', function()
{
    
}));*/

Event::listen('404', function()
{
	return Response::error('404');
});

Event::listen('500', function($exception)
{
	return Response::error('500');
});

/*
|--------------------------------------------------------------------------
| Route Filters
|--------------------------------------------------------------------------
|
| Filters provide a convenient method for attaching functionality to your
| routes. The built-in before and after filters are called before and
| after every request to your application, and you may even create
| other filters that can be attached to individual routes.
|
| Let's walk through an example...
|
| First, define a filter:
|
|		Route::filter('filter', function()
|		{
|			return 'Filtered!';
|		});
|
| Next, attach the filter to a route:
|
|		Route::get('/', array('before' => 'filter', function()
|		{
|			return 'Hello World!';
|		}));
|
*/

Route::filter('before', function()
{
    Session::put('ainopankki','');
    $route ="";
    if(!Cookie::has('lang'))
    {
        $lang = 'fin';
	Cookie::put('lang', $lang, time() + 60*60*24*30);
        $route = "login";
	//return Route::controller('login');
    }
    else {
	$lang = (string) Cookie::get('lang');
        $route = "home";
        //return Route::controller('home');
    }
    
    Session::put('lang', $lang);
    return Route::controller($route);
    
    //get('name');
	// Do stuff before every request to your application...
});

Route::filter('after', function($response)
{
	// Do stuff after every request to your application...
});

Route::filter('csrf', function()
{
	if (Request::forged()) return Response::error('500');
});

Route::filter('auth', function()
{
	if (Auth::guest()) return Redirect::to('login');
});

Route::get('logout', function() {
    Auth::logout();
    $language = Session::get('lang');
    Session::forget('lang');
    Session::flush();
    Session::put('lang',$language);
    return Redirect::to('home');
});

/*Route::filter('apiauth', function()
{
 
    // Test against the presence of Basic Auth credentials
    $creds = array(
        'username' => Request::getUser(),
        'password' => Request::getPassword(),
    );
 
    if ( ! Auth::attempt($creds) ) {
 
        return Response::json([
            'error' => true,
            'message' => 'Unauthorized Request'],
            401
        );
 
    }
 
});

Route::get('authtest', array('before' => 'apiauth', function()
{
    return View::make('hello');
}));*/