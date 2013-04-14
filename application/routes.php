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
Route::controller('home');

Route::controller(Controller::detect());
Route::get('about', 'home@about');
/*
Route::get('/', function()
{
	return View::make('home.index');
});    *//*
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

Route::get('login', function() {
    return View::make('login.index');
});

Route::get('lang', function() {
    $lang = Input::get('lang');
   // $lang = (string) $_GET['lang'];
    Cookie::put('lang', $lang, time() + 60*60*24*30);
    return View::make('home.index');
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
        return View::make('home.index');
    }
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
        'username'      => Input::get('username'),
        'password'      => Hash::make(Input::get('password'))
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

Route::get('home', array('before' => 'filter', function()
{
    
}));

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
    if(!Cookie::has('lang'))
    {
        $lang = 'fin';
	Cookie::put('lang', $lang, time() + 60*60*24*30);
	return View::make('login.index');
    }
    else {
	$lang = (string) Cookie::get('lang');
        return View::make('home.index');
    }
    Session::put('lang', $lang);
    
    
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