@extends('block@block')

@section('inner')
    @foreach($fields as $field)
        {!! $field->render() !!}
    @endforeach
@overwrite