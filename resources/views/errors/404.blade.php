<!DOCTYPE html>
<html>
    <head>
        <title>Errore 404 - Pagina non trovata</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                color: #B0BEC5;
                display: table;
                font-weight: 100;
                font-family: 'Lato', sans-serif;
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
                font-size: 74px;
                line-height: 1em;
            }
            .error {
                display: block;
                font-size: 2em;
                margin: 0 auto 0.2em auto;
                color: #0093d5;
                font-family: Gudea, "Helvetica Neue", HelveticaNeue, TeXGyreHeros, "Nimbus Sans L", "Liberation Sans", Helvetica, Arial, sans-serif;
                opacity: 0.5;
            }

        </style>
    </head>
    <body>
        <div class="container">
            <h1 class="content"><span class="error">404</span>Pagina non trovata.</h1>
            <h2 style="margin-top:-20px">Siamo spiacenti, la pagina che hai richiesto non è stata trovata.
                    Per favore torna sulla <a href="/">homepage</a></h2>
            @if (\Auth::check())
                <h2>
                    <a href="{{  url('admin/dashboard') }}" class="btn btn-large btn-info">
                        <i class="glyphicon glyphicon-home"></i> oppure vai alla Dashboard
                    </a>
                </h2>
            @endif
        </div>
    </body>
</html>
