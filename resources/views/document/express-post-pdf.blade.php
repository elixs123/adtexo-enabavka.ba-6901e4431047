<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
        <meta charset="utf-8" />
        <title>{{ $title }} - enabavka.ba</title>
        <style>
            html, body {
                width: 100%;
                height: 100%;
                margin: 0;
                padding: 0;
            }
            embed {
                width: 100%;
                height: 100%;
            }
        </style>
    </head>
    <body style="margin: 0;padding: 0;">
        <embed type="application/pdf" src="data:application/pdf;base64,{!! $base64 !!}" />
    </body>
</html>
