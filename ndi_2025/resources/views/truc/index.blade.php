c'est un test

<a href="{{ route('trucMachine') }}">trucMachine</a>

<script src="{{ asset('js/tkt.js')}}"></script>

<link rel="stylesheet" href="{{ asset('style/style.css')}}">


routes/web:
<br>
<br>
les routes

<br>

Route::get('/PATH', function () {
    return view('view.route'); -> dossier resource sous fichier = . etc
});
<br>
<br>

JS ET CSS
<br>

dans public/...
<br>

pour les appelers c'est:
@verbatim
{{ asset('js/tkt.js')}}
{{ asset('css/style.js')}}
@endverbatim
<br>

view dans ressource/views
<br>

nom.blade.php

<br>



les trucs utiles dans les vues:

@verbatim

<div class="blade-card">
    <h1>📘 Rappels des bases Blade (Laravel)</h1>

    <h2>1. Conditions : @if / @elseif / @else</h2>
    <div class="blade-block">
        <pre>@if($age > 18)
    Vous êtes majeur
@elseif($age == 18)
    Vous venez d’être majeur !
@else
    Vous êtes mineur
@endif</pre>
    </div>

    <h2>2. Condition inverse : @unless</h2>
    <div class="blade-block">
        <pre>@unless($isAdmin)
    Vous n’êtes pas admin.
@endunless</pre>
    </div>

    <h2>3. Variables : @isset / @empty</h2>
    <div class="blade-block">
        <pre>@isset($name)
    Bonjour {{ $name }}
@endisset

@empty($users)
    Aucun utilisateur.
@endempty</pre>
    </div>

    <h2>4. Boucles : foreach / forelse / for / while</h2>
    <div class="blade-block">
        <pre>@foreach($users as $user)
    {{ $user->name }}
@endforeach

@forelse($users as $user)
    {{ $user->name }}
@empty
    Aucun utilisateur trouvé.
@endforelse

@for($i = 0; $i < 10; $i++)
    Nombre : {{ $i }}
@endfor

@while($i < 5)
    {{ $i }}
    @php $i++; @endphp
@endwhile</pre>
    </div>

    <h2>5. Layouts : @section / @yield / @extends</h2>
    <div class="blade-block">
        <pre>// layout.blade.php
&lt;body&gt;
    @yield('content')
&lt;/body&gt;

// page.blade.php
@extends('layout')

@section('content')
    &lt;h1&gt;Hello World&lt;/h1&gt;
@endsection</pre>
    </div>

    <h2>6. Commentaires Blade</h2>
    <div class="blade-block">
        <pre>{{ "-- Blade comment --" }}
{{-- Ceci est un commentaire Blade, non visible au rendu --}}
</pre>
    </div>

    <h2>7. Affichage Blade</h2>
    <div class="blade-block">
        <pre>{{ $variable }}   // échappé
{!! $html !!}   // non échappé</pre>
    </div>

    <h2>8. Switch : @switch</h2>
    <div class="blade-block">
        <pre>@switch($role)
    @case('admin')
        Administrateur
        @break

    @case('user')
        Utilisateur
        @break

    @default
        Inconnu
@endswitch</pre>
    </div>

    <h2>9. Authentification : @auth / @guest</h2>
    <div class="blade-block">
        <pre>@auth
    Vous êtes connecté.
@endauth

@guest
    Vous êtes invité.
@endguest</pre>
    </div>
</div>
@endverbatim
