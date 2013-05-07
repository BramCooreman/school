@layout('layouts/main')
 @section('body')
 <?php print '<body class="' . Session::get('sivu'). '">'; ?>
    @section('pohja')
     {{ render('home.header') }}
     {{ render('home.loginbar') }}
     {{ render('home.navi') }}
     {{ render('login.index') }}
     {{ render('home.footer') }}
     @endsection
@endsection