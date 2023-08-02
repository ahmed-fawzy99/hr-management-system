@extends('errors::layout')

@section('title', __('Service Unavailable'))
@section('code', '503')

@section('message')
    <div style="font-weight: bold">503 | Sorry, we are now undergoing daily maintenance.<br>Please check back in a minute.</div>
@endsection
