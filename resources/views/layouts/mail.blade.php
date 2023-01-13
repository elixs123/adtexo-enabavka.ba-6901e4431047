<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>@yield('title')</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="initial-scale=1.0,width=device-width" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600" rel="stylesheet" type="text/css" />
    <style type="text/css">
@php include resource_path('assets/mail/mail.css') @endphp
    </style>
</head>
<body>
<!-- Preview text -->
<!--[if !gte mso 9]><!--><span class="mcnPreviewText" style="display: none; font-size: 0px; line-height: 0px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; visibility: hidden; mso-hide: all;">@yield('preview')</span>
<!--<![endif]-->
<!-- Preview text -->
<!-- Wrapper -->
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="wrapper">
    <tr align="center">
        <td width="100%" align="center" valign="top">
            <!-- Header -->
            <table border="0" cellspacing="0" cellpadding="0" class="header">
                <tr>
                    <td class="title"><strong>@yield('title')</strong></td>
                    <td class="domain"><a href="{{ url('/') }}">{{ get_domain_name() }}</a></td>
                </tr>
            </table>
            <table border="0" cellspacing="0" cellpadding="0" class="header">
                <tr>
                    <td class="logo">
                        <img src="{{ asset('assets/img/adtexo_logo_20200406.jpg').assetVersion() }}" style="" />
                    </td>
                </tr>
                <tr>
                    <td class="date">{{ \Carbon\Carbon::now()->format('d.m.Y H:i') }}</td>
                </tr>
            </table>
            <!-- Header -->
            @yield('content')
            <!-- Footer -->
            <table border="0" cellspacing="0" cellpadding="0" class="footer">
                <tr>
                    <td>
                        <p>Ovaj mail je poslan na adresu <strong><a href="mailto::{!! $data['user_to_email'] !!}">{!! $data['user_to_email'] !!}</a></strong></p>
                    </td>
                </tr>
            </table>
            <!-- Footer -->
        </td>
</table>
<!-- Wrapper -->
</body>
</html>
