@section('content')
<?php
if(Input::has('sivu'))
{
    $sivu = Input::get('sivu');
    
    
    
}
if (isset($_GET[ 'sivu' ])) {
    Response::view($sivu)      ;
        $sivu = $_GET[ 'sivu' ] . '.php';
        
        if (file_exists($sivu)) {
            
                require_once $sivu;
        } else {
              //  print '<p>'.Functions::localize('Hakemaasi sivua ei löydy').'</p>';
        }
    } else {?>
           {{ render('login.index') }} 
 <?php   }
?>
@endsection
<!-- /content -->