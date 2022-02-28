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
                    <div class="container">
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
                            <div class="container">
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
								                <div class="variable-width gallery">
                                                @if(isset($media->gallery->images))

                                                    @foreach($media->gallery->images as $image)

                                                            <div class="inhaltbildergalerie">
                                                              <img alt="{{$image->alt}}" class="#" src="{{route('image', $image->image)}}">
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
@if(isset($parameters['pdf']))
<div class="footer-background p-3 my-3 border">
    <a href="@isset($parameters){{route('download', $parameters)}}@endisset" class="btn m-4" data-toggle="modal" data-target="#previewModal" target="_blank" >{{__('pdf')}} <i class="bi bi-file-earmark-pdf-fill"></i>
    </a>
</div>
@endif
<footer>
    <div class="footerinner">

        <p id="footeradresse">Schreinerstraße 59 <br> 10247 Berlin</p>
        <ul><li id="emailadressefooter"><a class="verlinkung" href="#">mail@berlinhistory.app</a></li></ul>
        <ul id="verlinkungslistefooter" >
            <li class="footerverlinkung"><a class="verlinkung" href="{{route('preview.metadata', ['type' => 'copyright','parameters' => $parameters])}}">{{__('copyright')}}</a> </li>
            <li class="footerverlinkung"> <a class="verlinkung" href="{{route('preview.metadata', ['type' => 'policy','parameters' => $parameters])}}">{{__('policy')}}</a> </li>
        </ul>
    </div>
</footer>
@section('footer')
    @if(Auth::user()->can('publish-project', $project->user_id) || Auth::user()->can('preview'))
        <div class="footer-background p-3 my-3 border">
            <a href="#" class="m-4" data-toggle="modal" data-target="#previewModal" target="_blank" >{{__('pdf')}} <i class="bi bi-file-earmark-pdf-fill"></i>
            </a>
            <a href="#" class="m-4" data-toggle="modal" data-target="#previewModal" target="_blank" >{{__('preview')}} <i class="bi bi-globe"></i>
            </a>
            <span class="right">	<a href="https://app.crowdcurat.io/downloads/html.zip" class="m-4"  target="_blank" >{{__('download')}} <i class="bi bi-globe"></i>
            </a></span>
        </div>
    @endif
@endsection

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
