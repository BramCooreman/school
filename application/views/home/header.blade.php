
@section('palkki')

{{ Notifications::printNotification() }}

<small><a href=lang/fin style="padding-left:25px;"><?php echo HTML::image('images/Finland-icon.png',"suomi")?></a></small>      <small><a href=lang/en-us style="padding-left:15px;"><?php echo HTML::image("images/GreatBritain-icon.png","english");?></a></small>	
@endsection
@section('image')
{{ HTML::image("images/ainologo.png","Ainopankin logon kuva", array('id' => 'ainoLogo')) }}
@endsection