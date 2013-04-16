@section('content')
<?php
if(Input::has('sivu'))
{
    $sivu = Input::get('sivu');
    Session::put('sivu',Input::get('sivu'));
}
if (isset($_GET[ 'sivu' ])) {
   // Route::controller($sivu)      ;
    Session::put('sivu',$sivu);
   echo View::make($sivu.'.index');
   
 //echo   Redirect::to($sivu);
        
      
            
                      //  print '<p>'.Functions::localize('Hakemaasi sivua ei löydy').'</p>';
        
    } else {?>
           {{ render('login.index') }} 
 <?php   }
?>
@endsection
<!-- /content -->