@extends('master')

@section('title', $page->title)
@section('ogTitle', $page->title)@section('description', $page->description)
@section('keywords', $page->keywords)


@section('css')
    @if($page->css)
        <style type="text/css">
            {{ $page->css }}
        </style>
    @endif
@endsection

@section('javascript')
    @if($page->javascript)
        <script>
            {{ $page->javascript }}
        </script>
    @endif
@endsection

@section('footer')
    @parent
    <p>This is appended to the master sidebar.</p>
@endsection

// nella master
@section('header')
    <header class="site-header">
        {!! $theme->getFrame('intestazione') !!}
    </header>
@show

@section('sidebar')
    SSSSSSSSSSSSSSSSSSSSS
@show