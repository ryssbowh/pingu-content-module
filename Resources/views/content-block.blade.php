@extends('block@block')

@section('inner')
<h1>And I'm a content block for content {{ $content->title }}</h1>
@overwrite