@extends('layouts.mail')

@section('title', $data['title'])

@section('preview', $data['preview'])

@section('content')
<!-- Content -->
<table border="0" cellspacing="0" cellpadding="0" class="content">
    <tr>
        <td class="space border_bottom">
            <p>{!! $data['message'] !!}</p>
            <p>File: <strong>{{ $data['file'] }}</strong></p>
            <p>Line: <strong>{{ $data['line'] }}</strong></p>
            <p><br>User: @if(!is_null($user['name']))<strong>{{ $user['name'] }}</strong>@endif / <strong>{{ $user['mail'] }}</strong></p>
        </td>
    </tr>
</table>
@if(isset($request))
<table border="0" cellspacing="0" cellpadding="0" class="content">
    <tr>
        <td class="section">
            <p><strong>Request</strong></p>
        </td>
    </tr>
</table>
<table border="0" cellspacing="0" cellpadding="0" class="content">
    <tr>
        <td class="space border_bottom request">
            @foreach($request as $key => $val)
            <p><span>{!! $key !!}</span>: {!! $val !!}</p>
            @endforeach
        </td>
    </tr>
</table>
@endif
@if(count($query))
<table border="0" cellspacing="0" cellpadding="0" class="content">
    <tr>
        <td class="section">
            <p><strong>Query</strong></p>
        </td>
    </tr>
</table>
<table border="0" cellspacing="0" cellpadding="0" class="content">
    <tr>
        <td class="space border_bottom request">
            @foreach($query as $key => $val)
            <p><span>{!! $key !!}</span>: {!! $val !!}</p>
            @endforeach
        </td>
    </tr>
</table>
@endif
<!-- Content -->
@endsection
