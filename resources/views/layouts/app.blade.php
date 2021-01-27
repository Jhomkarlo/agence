@php
    $routeName = Route::currentRouteName();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Agence - @yield('title')</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <v-app id="app">
        <v-app-bar
            color="#6A76AB"
            dark
            app
            >
            <template v-slot:img="{ props }">
                <v-img
                    v-bind="props"
                    gradient="to top right, #323232,  #3F3F3F, #1C1C1C"
                ></v-img>
            </template>
            <v-toolbar-title href="{{ route('agence.index') }}">
                <img  src="{{ asset('img/logo.gif') }}" style="max-width: 110px; filter: brightness(0) invert(1);" alt="Agence"/>
            </v-toolbar-title>
            <template v-slot:extension>
                <a-nav currentroutename={{ $routeName }} :routes={{ json_encode([
                    ['id' => 0, 'uri' => route('agence.index'), 'label' => 'Agence', 'name' => 'agence.index'],
                ]) }}></a-nav>
            </template>
        </v-app-bar>
        <v-main id="main" class="{{$routeName}}">
            @yield('content')
        </v-main>
    </v-app>
    
</body>
</html>