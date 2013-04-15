<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi" lang="fi">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/> 
        <title>Ainopankki</title>
        {{ HTML::style('css/ainopankki_style.css'); }}
        {{ HTML::script('js/jquery-1.8.2.min.js'); }}
        <link rel="shortcut icon" href="images/aino_favicon.png" />
        {{ HTML::style('css/calendar/calendar.js'); }}
        {{ HTML::script('js/calendar/calendar.js'); }}
        {{ HTML::script('js/disable_enter.js'); }}
        {{ HTML::script('js/confirm.js'); }}
    </head>
      @yield('body')
    
      <div id="pohja">
      @yield('pohja')
         <div class ="palkki">
         @yield('palkki')  
         </div>
         @yield('image')
         <div id ="loginbar">
         @yield('loginbar')  
         </div>
         <div id ="left">
         @yield('left')  
         </div> 
         <div id ="content">
         @yield('content')
         </div>
         <div id ="footer">
         @yield('footer')  
         </div>
      </div>
    </body>
</html>