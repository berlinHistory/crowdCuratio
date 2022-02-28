<!--
crowdCuratio - Curating together virtually
Copyright (C)2022 - berlinHistory e.V.

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program in the file LICENSE.

If not, see <https://www.gnu.org/licenses/>. -->

<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>CrowdCuratio</title>

    {{--<style type="text/css" media="all">
        @include('preview.style')
    </style>--}}

    <link media="all" rel="stylesheet" type="text/css" href="{{ asset('css/index.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('slick/slick.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('slick/slick-theme.css') }}"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/gsap.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/ScrollToPlugin.min.js"></script>
    <script type="text/javascript" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/16327/gsap-latest-beta.min.js"></script>
    <script type="text/javascript" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/16327/ScrollTrigger.min.js?v=3.3.0-3"></script>
    <script type="text/javascript" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/16327/gsap-latest-beta.min.js"></script>
    <style type="text/css">
        /*accent color*/
        .accent{
            @if(isset($parameters['colorAccent'])) background-color: {{$parameters['colorAccent']}} @endif;
        }
        /*font color*/
        h4,
        .slick-dots li.slick-active button::before{
            @if(isset($parameters['colorChapter'])) color: {{$parameters['colorChapter']}} !important @endif;
        }
    </style>
</head>

<body onresize="toggleback()">
<div>
    <div class="top-container accent">
        <div id="hinweis">
            <div class="top-inner">
                <p id="hinweistext">erstellt mit dem OpenSource Projekt </p>
                <img src="{{ asset('image/crowdCuratio.png') }}" id="logo2" width="1174" height="402" alt="" /></div>
        </div>
    </div>

    <header class="headerleiste accent remove-color" id="myHeader" >
        <div class="header-inner mb-4">
            <div>
                <a href="#" ><img class="logo" src="@if(isset($project->logo)){{route('image', $project->logo)}}@endif" alt="" ></a>
            </div>
            <p id="untertitel">{!! $project->description !!}</p>
            <p id="titel">
                <a href="index.html"></a>@isset($project->name){{$project->name}}@endisset
            </p>
        </div>

        <div id="burgermenu" onClick="toggle('sprachebtn')"> <span id="burgerbutton">
        <i class="fa fa-language" id="spracheicon"></i></span></div>
        <ul id="" class="accent">
            @if(!in_array(Route::currentRouteName(),['translate','log.detail']))
                @foreach (Config::get('languages') as $lang => $language)
                    <a href="{{ route('lang.switch', $lang) }}">
                        <button class="sprache" id="sprachede">{{$language}}</button>
                    </a>
                @endforeach
            @endif
        </ul>
        <nav class="ankerleiste">
            <div class="ankerpunkte">
                @if(isset($project->chapters))
                    @foreach($project->chapters as $keyProject => $value)
                        <a href="#section{{$keyProject}}" id="anker{{$keyProject}}" class="anker">{{$value->name}} </a>
                    @endforeach
                @endif
            </div>
        </nav>
    </header>

    <section class="einleitung">
        <div class="container">
            <div class="zweispaltig">
                @if(isset($project->description))
                    <p>{!! $project->description !!}</p>
                @endif
            </div>
        </div>
    </section>

</div>

<div class="container">
    @if(isset($content))
        <h1>{{__($type)}}</h1>
        <div class="mb-4">
            {!! $content !!}
        </div>
    @endif
    <div class="mt-4">
        <a href="{{ url()->previous() }}"> < {{__('back')}}</a>
    </div>
</div>

<script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
{{--<script type="text/javascript">
    document.write('<scr'+'ipt type="text/javascript" src="{{ URL::asset('js/scrollmagic/uncompressed/ScrollMagic.js') }}"></scr'+'ipt>');
</script>--}}


</body>
</html>
