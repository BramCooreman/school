
@section('loginbar')
@if(Session::has('Authenticated'))
    <p> {{ Functions::localize('Kirjautuneena sisään') }}<br/><strong> {{ Session::get('yhtionNimi') }} / {{ Session::get( 'kayttaja' )}}</strong></p>
    <p><a href=logout> {{ Functions::localize('Kirjaudu ulos') }} </a></p>
@else
    <p> {{ Functions::localize('Et ole kirjautuneena sisään') }}</p>
    <p><a href=home> {{ Functions::localize('Kirjaudu sisään') }} </a></p>
@endif
@endsection
