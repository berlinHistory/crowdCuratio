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

        a:link,
        a:visited{
            color: rgba(163,163,163,0.84);
            text-decoration: none;
        }
        a:hover{
            color: rgba(91,91,91,0.90);
        }

        .top-container {

            height: 1em;
        }

        /* Header */
        #spracheicon{
            color: white;
            font-size: 0.4em;
        }
        #hinweis {
            width: 38%;
            box-sizing: border-box;
            border-radius: 0px 0px 0px 14px;
            background-color: rgba(0,0,0,0.7);
            float: right;
            height: 1.5em;
        }
        #hinweistext {
            color: white;
            padding-left: 4.3em;
            padding-top: 0.5em;
            font-family: Inter,semibold;
            font-size: 0.6em;

        }
        .headerleiste {
            height: 80px;
            margin-bottom: 0.7em;
            z-index: 80;
        }
        #logo2 {
            width: 7em;
            height: auto;
            margin-left: 14em;
            margin-top: -1em;
        }
        .logo {
            position: absolute;
            width: 50px;
            height: auto;
            display: block;
            margin-top: 6px;
        }



        #untertitel {
            margin-left: 80px;
            top: 10px;
            position: relative;
            color: white;
            font-family: Inter,semibold;
            font-size: clamp(10px,15px,50px);
        }

        #titel {
            margin-left: 80px;
            top: 8px;
            position: relative;
            color: white;
            font-family: Inter,bold;
            font-size: clamp(10px,25px,40px);
            font-weight: bold;
        }

        .sticky {
            position: fixed;
            top: 0;
            width: 100%;
        }

        .header-inner{
            width: clamp(400px, calc( 100% - 25px),960px);
            margin-left: auto;
            margin-right: auto;
        }

        .top-inner{
            margin-right: 135px;
        }


        .sprache {
            border: none;
            outline: none;
            padding: 10px 16px;
            cursor: pointer;
            font-size: 18px;
            color: white;
            font-family: Inter,bold;
            float: right;
            margin-top: -1em;
            background: none;
        }

        .activesprache, .sprache:hover {
            background-color: white;
            color: black;
            border-radius: 8px;
        }

        .activesprache {
            text-decoration: none;
            color: black;
        }
        /*Einleitungsberich*/

        .ankerleiste{
            background-color: white;
            width: 100%;
            margin-top: -0.5em;
        }


        .ankerpunkte {
            color: #6F6F6F;
            text-decoration: none;
            z-index: 999;
            margin-top: 18px;
            width: clamp(400px, calc( 100% - 250px),960px);
            margin-left: auto;
            margin-right: auto;
            padding-top: 10px;
            padding-bottom: 10px;

        }

        .anker{
            display: inline-block;
            font-size: 1.1em;
            font-family: Inter;
            color: rgba(141,141,141,1.00);
            text-decoration: none;
            padding-right: 10px;
            word-break: keep-all;
            font-weight: 500;
        }
        .anker:link,
        .anker:visited{
            color: rgba(49,49,49,0.89);
            text-decoration: none;
        }
        .anker:hover{
            color: rgba(0,0,0,1.00);
        }

        .active {
            text-decoration: none;
            color: #EDBA0E;
        }

        .sticky + .einleitung {
            margin-top: 6em;
        }

        /*container */

        .einleitung .zweispaltig p{
            margin: 2em 0 1em;
        }


        .container{
            width: clamp(400px, calc( 100% - 500px),960px);
            margin-left: auto;
            margin-right: auto;
        }

        .hintergrundgrau{
            background-color: rgba(152, 140, 140, 0.55);
            padding-top: 2em;
            padding-bottom: 2em;
        }

        .hintergrundweiss{
            padding-top: .5em;
            padding-bottom: .5em;
        }


        .zweispaltig {
            columns: 2 5em;
            -ms-hyphens: auto;
            -webkit-hyphens: auto;
            hyphens: auto;
            color: #454545;
            text-align: left;
            /*margin-bottom: 3em;*/
            font-size: clamp(0.95rem, calc( 3px + 0.025vw ), 2.163rem);
            margin: .5em 0 1em;
        }
        .zweispaltig p{
            margin: 0em 0 0 0;
        }
        .einspaltig h2{
            margin: 3em 0 .8em;
        }
        .einspaltig p{
            margin: .3em 0 .6em 0;
        }

        .subtitle{
            /*font-weight: 600;
            font-size: 1.1rem;*/
        }
        h1, h2, h3, h4, h5, h6 {
            font-weight: 600;
            font-size: 1.2rem;
            color: rgba(34,34,34,0.65);
        }


        .caption {
            position: absolute;
            background: rgba(45,45,45,0.65);
            display: block;
            bottom: 0;
            right: 0;
            left: 0;
            padding: .7em 1em 1.5em;
            color: rgba(255,255,255,0.85);
            max-width: 810px;
        }


        h4 {
            color: #EDBA0D;
            font-size: clamp(1.338rem, calc( 4px + 2.025vw ), 2.163rem);
            font-family: Inter,bold;
            margin-top: 1em;
            margin-bottom: 1em;
            font-weight: bold;
            width: clamp(200px, calc( 100% - 250px),960px);
            margin-left: auto;
            margin-right: auto;

        }

        .plus {
            font-size: clamp(0.8rem, calc( 3px + 1.025vw ), 2.163rem);
            color: #333232;
            width:  clamp(344px, calc( 99% - 200px),1015px);
            margin-left: auto;
            margin-right: auto;
            margin-top: -3.4em;
            position: relative;
            padding-left: 1em;
            height: 2em;

        }

        .plus::before {
            content: '▼';
            display: block;
            position: absolute;
            transition: transform .1s ease;
            transform: rotate(-0deg);
            left: 0;
            line-height: 2.3em;
            color: rgba(0,0,0,0.50);
            cursor: pointer;
            transition:.4s all;
        }
        .plus:hover::before {
            color: rgba(0,0,0,1);
        }


        .rotate.plus::before {
            transform: rotate(-90deg);
        }
        .titelpfeil{
            margin-left: -1em;
            position: absolute;
            color: #988C8C;
            font-family: Inter,bold;
        }
        .bereich-grau {
            font-size: 1.5em;
            color: white;
            padding-bottom: 1em;
            font-family: Inter,bold;
        }

        .bereich-weiss{
            font-size: 1.5em;
            color: #EDBA0D;
            padding-bottom: 1em;
            font-family: Inter,bold;
        }



        .dropdown{
            display: none;
        }

        .dropdown.show{
            display: block;
        }

        /*Bildergalerie */


        .slick-slide{
            float: none !important;
        }

        .slick-slide img {
            display: block;
            max-width: 100%;
            max-height: 600px;
            object-fit: contain;
            margin-left: auto;
            margin-right: auto;
        }


        .slick-list{
            background-color: #2D2D2D;
        }

        .slick-dots{
            bottom: 5px !important;
        }
        .slick-dots li.slick-active button::before {
            color: orange !important;
            opacity: 0.75;
            font-size:10px !important;
        }

        .slick-dots li button{
            font-size:10px !important;
        }

        .slick-dots li button::before{
            font-size: 10px !important;
            opacity: 0.55 !important;
        }


        .bildunterschrift{
            color: #454545;
            font-family: Inter;
            text-align: left;
            font-size: clamp(0.8rem, calc( 3px + 0.025vw ), 2.163rem);
            padding-bottom: 15px;
        }

        .inhaltbildergalerie{
            display: inline-block !important;
            height: 100% !important;
            vertical-align: middle !important;
        }
        .slick-slide:not(.slick-current){visibility: hidden}

        /*footer*/
        footer {
            background-color: rgba(21,28,31,0.68);
            height: 220px;
            margin-top: 3em;
        }
        #Logofooter {
            width: 10em;
            height: auto;
            border-bottom-color: white;
            border-bottom-width: 0.5px;
            border-bottom-style: solid;
            margin-top: 1em;
            padding-bottom: 1em;
            padding-right: 6em;
        }
        #footeradresse {
            color: white;
            font-family: Inter;
            margin-bottom: 0.5em;
            font-size: 0.8em;
            margin-top: 2em;
        }

        #emailadressefooter {
            text-decoration: none;
            list-style: none;
            color: #88B6C7;
            font-family: Inter;
            margin-bottom: 0.5em;
            font-size: 0.8em;
        }
        .verlinkung {
            text-decoration: none;
            color:#88B6C7;
        }
        .footerverlinkung {
            display: inline;
            border-right-color: white;
            border-right-width: 1px;
            border-right-style: solid;
            font-size: 0.8em;
            text-decoration: none;
            font-family: Inter;
            color:#88B6C7;

        }


        .footerinner{
            width: clamp(200px, calc( 100% - 100px),960px);
            margin-left:auto;
            margin-right:auto;
        }

        #sprachebtn {
            display: block;
            margin-right: 0em;
            background-color: #EDBA0D;
            padding: 10px;
            margin-left: -3.45em;
            z-index: 1;

            padding-right: 1em;
        }
        #burgerbutton {
            display: none;
        }

        @media (max-width: 900px) {
            .zweispaltig{
                columns: 1 100vw;
            }

            .top-container{
                display:none;
            }
            #burgermenu{
                float: right;
                font-size: 5em;
                color: white;
                margin-top: -0.75em;
                margin-right: 0.5em;
                height: 0em;
            }

            #sprachebtn{
                display:none;
            }

            #burgerbutton {
                color: #000;
                font-size: 4rem;
                margin: 0rem -2rem 0 0;
                float: right;
                position: relative;
                padding: 0 2rem;
                display: block;
            }
        }

        @media (max-width: 570px){
            #burgerbutton {
                color: #000;
                font-size: 4rem;
                margin: 0.5rem -2rem 0 0;
                float: right;
                position: relative;
                padding: 0 2rem;
                display: block;
            }
            .sprache {
                border: none;
                outline: none;
                padding: 10px 16px;
                cursor: pointer;
                font-size: 14px;
                color: white;
                font-family: Inter,bold;
                float: right;
                margin-top: -1em;
                background: none;
            }
        }


        @media (max-width: 525px){


            .header-inner{
                width: 100%;
            }


            #untertitel{
                display:none;
            }
            .ankerpunkte{
                margin-top: 36px;
            }

            #titel{
                top: 22px;
                width: 70%;
            }


            .einleitung {
                margin-top: 5em;
            }

            .container{
                width: 90%;
            }

            .ankerpunkte{
                width: 90%;
            }

            h4{
                width: 75%;
            }

            .plus{
                width: 90%;
            }

            .slick-slide img{
                max-height: 300px;
            }

            #burgerbutton {
                color: #000;
                font-size: 4rem;
                margin: 1rem -2rem 0 0;
                float: right;
                position: relative;
                padding: 0 2rem;
                display: block;
            }
            .sprache {
                border: none;
                outline: none;
                padding: 13px 14px;
                cursor: pointer;
                font-size: 14px;
                color: white;
                font-family: Inter,bold;
                float: right;
                margin-top: 0em;
                background: none;
            }
        }





        @media (max-width: 390px){




            #titel{
                margin-left: 60px;
                top: 22px;
                font-size: 22px;
                width: 80%;
            }


            .einleitung {
                margin-top: 4em;
            }

            .ankerpunkte{
                margin-top: 38px;
            }

            .header-inner{
                width: 90%;
            }
            #burgerbutton {
                color: #000;
                font-size: 4rem;
                margin: 1.5rem -2.5rem 0 0;
                float: right;
                position: relative;
                padding: 0 2rem;
                display: block;
            }
            .remove-color{
                background-color: white !important;
            }
        }

        #section0 .container #text.zweispaltig{
            margin-bottom: -2.0em;
        }

    </style>
</head>

<body onresize="toggleback()">
<div>
    <div class="top-container accent">
        <div id="hinweis">
            <div class="top-inner">
                <p id="hinweistext">erstellt mit dem OpenSource Projekt </p>
                <img src="{{ public_path().'/image/crowdCuratio.png' }}" id="logo2" width="1174" height="402" alt="" /></div>
        </div>
    </div>

    <header class="headerleiste accent remove-color" id="myHeader" >
        <div class="header-inner mb-4">
            <div>
                <a href="#" ><img class="logo" src="@if(isset($project->logo)){{public_path().'/uploads/images/'.$project->logo}}@endif" alt="" ></a>
            </div>
            <p id="untertitel">{!! $project->description !!}</p>
            <p id="titel">
                <a href="index.html"></a>@isset($project->name){{$project->name}}@endisset
            </p>
        </div>

        <div id="burgermenu" onClick="toggle('sprachebtn')"> <span id="burgerbutton">
        <i class="fa fa-language" id="spracheicon"></i></span></div>
        <ul id="" class="">
            @if(!in_array(Route::currentRouteName(),['translate','log.detail']))
                @foreach (Config::get('languages') as $lang => $language)
                    <a href="{{ route('lang.switch', $lang) }}">
                        <button class="" id="sprachede">{{$language}}</button>
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
        <div class="">
            <div class="zweispaltig">
                @if(isset($project->description))
                    <p>{!! $project->description !!}</p>
                @endif
            </div>
        </div>
    </section>

</div>

<!---------------- DYNAMIC SECTIONS ------------------->

@if(isset($project))
    @if(isset($project->chapters))
        @foreach($project->chapters as $k => $chapter)
            <h4 class="toggledown"> {{$chapter->name}} </h4>
            @if(isset($parameters['collapse']))
                <div class="plus" onclick="addText({{$k}})"></div>
            @endif
            <section id="section{{$k}}" class="section einleitung{{$k}}">
                <div class="hintergrundweiss">
                    <div class="">
                        <div class="zweispaltig" id="text">
                            @isset($chapter->title)<h2>{!! $chapter->title !!}</h2>@endisset
                            @isset($chapter->subtitle)<h3>{{$chapter->subtitle}}</h3>@endisset
                            @isset($chapter->description)<p>{!! $chapter->description !!}</p>@endisset
                        </div>

                    </div>
                </div>

                @if(isset($chapter->entries))
                    @foreach($chapter->entries as $key => $entry)
                        <div class="@if($key == 0 ) hintergrundweiss @elseif($key%2 == 0) hintergrundweiss @else {{$parameters['backgroundSecond']}} @endif">
                            <div class="">
                               <!-- <h5 class="bereich-grau"></h5>-->
                                <div class="zweispaltig">
                                    @isset($entry->name)<h2>{{$entry->name}}</h2>@endisset
                                    @isset($entry->subtitle)<p class="subtitle">{{$entry->subtitle}}</p>@endisset
                                    @isset($entry->description)<p>{!! $entry->description !!}</p>@endisset
                                </div>

                                @if(isset($entry->mediaContent))
                                    @foreach($entry->mediaContent as $media)
                                        @if(isset($media->media_contentable_type))
                                            @if($media->media_contentable_type == 'App\Models\Text' && isset($media->text->text))
                                                <div class="einspaltig">
                                                    <p>{!! $media->text->text !!}</p>
                                                </div>
                                            @endif
                                            @if($media->media_contentable_type == 'App\Models\Image')
                                <div class="einspaltig">
                                    @isset($media->gallery->title)<h2>{!! $media->gallery->title !!}</h2>@endisset
                                    @isset($media->gallery->subtitle)<p class="subtitle">{!! $media->gallery->subtitle !!}</p>@endisset
                                    @isset($media->gallery->description)<p>{!! $media->gallery->description !!}</p>@endisset
                                </div>
								                <div class="">
                                                @if(isset($media->gallery->images))

                                                    @foreach($media->gallery->images as $image)

                                                            <div class="inhaltbildergalerie">
                                                              <img alt="{{$image->alt}}" class="#" src="{{public_path().'/uploads/images/'.$image->image}}">
																<p class="caption">{!! $image->alt !!}</p>
                                                           </div>
                                                    @endforeach
												@endif
                                                 </div>
                                            @endif
                                            @if($media->media_contentable_type == 'App\Models\Audiovisual')
                                                @if($media->audiovisual->type == 'audio')
                                                    <audio controls class="embed-responsive-item" id="audio" src="{{route('audio',$media->audiovisual->link)}}"  ></audio>
                                                @endif
                                                @if($media->audiovisual->type == 'video')
                                                    <div class="variable-width">
                                                        <div class="inhaltbildergalerie">
                                                            <iframe width="960px" height="400px" src="{!! $media->audiovisual->link !!}" frameborder="0" allowfullscreen>
                                                            </iframe>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                        @endif
                                    @endforeach
                                @endif
                            </div>

                        </div>
                    @endforeach
                @endif
            </section>
        @endforeach
    @endif
@endif
<div class="footer-background p-3 my-3 border">
    <a href="@isset($parameters){{route('download', $parameters)}}@endisset" class="m-4" data-toggle="modal" data-target="#previewModal" target="_blank" >{{__('pdf')}} <i class="bi bi-file-earmark-pdf-fill"></i>
    </a>
</div>
<footer>
    <div class="footerinner">

        <p id="footeradresse">Schreinerstraße 59 <br> 10247 Berlin</p>
        <ul><li id="emailadressefooter"><a class="verlinkung" href="#">mail@berlinhistory.app</a></li></ul>
        <ul id="verlinkungslistefooter" >
            <li class="footerverlinkung"><a class="verlinkung" href="#">Datenschutz</a> </li>
            <li class="footerverlinkung"> <a class="verlinkung" href="#">Impressum</a> </li>
        </ul>
    </div>
</footer>


<script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="{{ URL::asset('slick/slick.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('js/scrollmagic/uncompressed/ScrollMagic.js') }}"></script>
{{--<script type="text/javascript">
    document.write('<scr'+'ipt type="text/javascript" src="{{ URL::asset('js/scrollmagic/uncompressed/ScrollMagic.js') }}"></scr'+'ipt>');
</script>--}}


<script>
    function toggle() {
        x = document.getElementById('sprachebtn');

        if (x.style.display === 'block') {
            x.style.display = 'none';
        } else {
            x.style.display = 'block';
        }
    }



    function toggleback() {
        var w = window.innerWidth;

        if (w >= 900) {
            document.getElementById('sprachebtn').style.display ='block';
        } else{
            document.getElementById('sprachebtn').style.display ='none';
        }
    };

</script>



<!--Bildergalerie -->

<script type="text/javascript">



    $('.gallery').slick({
        dots: true,
        infinite: true,
        speed: 200,
        slidesToShow: 1,
        centerMode: true,
        variableWidth: true,
        arrows: false
    });



</script>

<!-- active Anchor -->

<script>


    //list as many as you'd like
    gsap.registerPlugin(ScrollToPlugin);

    var controller = new ScrollMagic.Controller({



    });

    $('.section').each(function (i, section){
        var $section = $(section);

        new ScrollMagic.Scene({
            triggerElement: section,
            triggerHook: 'onEnter',
            duration: $section.outerHeight(true)
        })
    })

</script>

<!-- Anchor Link Scrolling -->



<script>

    // Detect if a link's href goes to the current page
    function getSamePageAnchor (link) {
        if (
            link.protocol !== window.location.protocol ||
            link.host !== window.location.host ||
            link.pathname !== window.location.pathname ||
            link.search !== window.location.search
        ) {
            return false;
        }

        return link.hash;
    }

    // Scroll to a given hash, preventing the event given if there is one
    function scrollToHash(hash, e) {
        const elem = hash ? document.querySelector(hash) : false;
        if(elem) {
            if(e) e.preventDefault();
            gsap.to(window, 1, {scrollTo:{y:elem, offsetY:200}});
        }
    }

    // If a link's href is within the current page, scroll to it instead
    document.querySelectorAll('a[href]').forEach(a => {
        a.addEventListener('click', e => {
            scrollToHash(getSamePageAnchor(a), e);
        });
    });

    // Scroll to the element in the URL's hash on load
    scrollToHash(window.location.hash);


</script>


<!-- sticky Header -->


<script>
    window.onscroll = function() {myFunction()};

    var header = document.getElementById("myHeader");
    var sticky = header.offsetTop;

    function myFunction() {
        if (window.pageYOffset > sticky) {
            header.classList.add("sticky");
        } else {
            header.classList.remove("sticky");
        }
    }
</script>
<script>

    onload="noVis();"


    function noVis() {
        document.getElementsByClassName("section").style.display = "none";
    }

    function addText(i) {
        var content = document.getElementById("section" + i);
        if (content.style.display === "none") {
            content.style.display = "initial";
        }else{
            content.style.display = "none";
        }
    }
</script>
<script>
    var div = document.getElementById("sprachebtn");
    var btns = header.getElementsByClassName("sprache");
    for (var i = 0; i < btns.length; i++) {
        btns[i].addEventListener("click", function() {
            var current = document.getElementsByClassName("activesprache");
            if (current.length > 0) {
                current[0].className = current[0].className.replace(" activesprache", "");
            }
            this.className += " activesprache";
        });
    }
</script>
<script>
    $('.plus').click(function () {
        $(this).toggleClass('rotate')
    })
</script>
</body>
</html>
