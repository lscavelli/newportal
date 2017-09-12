<!DOCTYPE html>
<html>
    <head>
        <title>Be right back.</title>

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
            }

            .title {
                font-size: 72px;
                margin-bottom: 40px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="text-center">
                <div class="icon">
                    <i class="fa fa-lock"></i>
                </div>
                <h1>Forbidden!</h1>
                <br />
                <p>Non hai i permessi per accedere a questa pagina</p>
            </div>
        </div>
        @if (\Auth::check())
            <div>
                <a href="{{  route('dashboard') }}" class="btn btn-large btn-info">
                    <i class="glyphicon glyphicon-home"></i> Vai alla Dashboard
                </a>
            </div>
        @endif
    </body>
</html>
