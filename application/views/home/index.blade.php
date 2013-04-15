@layout('layouts/main')
 @section('body')
<?php print '<body class="' . $sivu . '">'; ?>

 
    @section('pohja')
     {{ render('home.header') }}
     {{ render('home.login') }}
     {{ render('home.navi') }}
     {{ render('home.content') }}
     {{ render('home.footer') }}
  <!--<?php echo render('home.header'); ?>-->
     @endsection
@endsection