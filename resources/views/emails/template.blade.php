@extends('layouts.mail')

@section('title', $data['title'])

@section('preview', $data['preview'])

@section('content')
<!-- Content -->
<table border="0" cellspacing="0" cellpadding="0" class="content">
    <tr>
        <td class="space border_bottom">
            <p>{!! $data['message'] !!}</p>
        </td>
    </tr>
</table>
@if(isset($data['button']))
<table border="0" cellspacing="0" cellpadding="0" class="content">
    <tr>
        <td class="center space border_bottom">
            <a href="{{ $data['button']['href'] }}" class="btn btn_primary">{!! $data['button']['text'] !!}</a>
        </td>
    </tr>
</table>
@endif
@if(isset($data['note']))
<table border="0" cellspacing="0" cellpadding="0" class="content">
    <tr>
        <td class="note center">
            <p>{!! $data['note'] !!}</p>
        </td>
    </tr>
</table>
@endif
<!-- Content -->
@endsection