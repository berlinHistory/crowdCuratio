@extends('projects.layout')
@section('content')
    @if (!env('APP_DEBUG', false))
        <p>{{Request::url()}}</p>
        <p>{{$exception->getMessage()}}</p>
        <p>Line: {{$exception->getLine()}}</p>
    @else
        <p>Error on page: {{Request::url()}}</p>
    @endif

@endsection