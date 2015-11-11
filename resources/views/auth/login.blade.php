<!DOCTYPE html>
<html>
    <head>
        <title>Bumin Uygulaması - Login</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                display: table;
                font-weight: 200;
                font-family: 'Arial';
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
                font-size: 30px;
            }
            .spanInfo {

                color: blue;

            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">Login</div>
                @if( \Session::has('neededLogin') )
                    <span class="spanInfo">{{\Session::get('neededLogin')}}</span>
                @endif                
                <form method="POST" action="/auth/login">
                    {!! csrf_field() !!}

                    <div>
                        Email
                        <input type="email" name="email" value="{{ old('email') }}">
                    </div>

                    <div>
                        Password
                        <input type="password" name="password" id="password">
                    </div>

                    <div>
                        <input type="checkbox" name="remember"> Remember Me
                    </div>

                    <div>
                        <button type="submit">Login</button>
                    </div>
                </form>


            </div>
        </div>
    </body>
</html>
