<!DOCTYPE html>
<html>
    <head>
        <title>Laravel</title>

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
                font-weight: 100;
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
                font-size: 20px;    
            }
        </style>
    </head>
    <body>

        @if(Auth::check())
        
            <h1>You are log in System !</h1>
            <h2> User: <a href="#" alt="User Details">{{$user->email}}</a> </h2>
        
        @endif
        <h3>Jobs</h3>
        <ul>            
            <li>Use Create</li>
            <li>User Update</li>
            <li>User info</li>
            <li>User </li>
        </ul>s       
    </body>
</html>
