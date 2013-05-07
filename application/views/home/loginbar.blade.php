@section('loginbar')
@if(Session::has('Authenticated'))
    <p> {{ Functions::localize('Kirjautuneena sisään') }}<br/><strong> {{ Session::get('yhtionNimi') }} / {{ Session::get( 'kayttaja' )}}</strong></p>
    <p>{{ HTML::link_to_action('logout', Functions::localize('Kirjaudu ulos') ) }}</p>
@else
    <p> {{ Functions::localize('Et ole kirjautuneena sisään') }}</p>
    <p>{{ HTML::link_to_action('home', Functions::localize('Kirjaudu sisään') ) }}</p>
@endif
@endsection
