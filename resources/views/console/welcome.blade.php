<!DOCTYPE html>
<html>
    <head>
        <title>Uygulama</title>

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

            .spanInfo {
                color :red;
            }
        </style>
    </head>
    <body>
        {{--  Check Login --}}
        @if(Auth::check())
        
            <h1>You are log in System !</h1>
            <h2> User: <a href="#" alt="User Details">{{$user->email}}</a> </h2>
        
        @endif

        
        {{-- Errors --}}
        @if( ! $errors->isEmpty() )
            <h5 class="spanInfo">Errors:</h5>
            <ul>
                @foreach($errors->all() as $msg)
                     <span class="spanInfo">{{$msg}}</span>
                @endforeach
            </ul>
           
        @endif

        <h3>Methods</h3>
        <ul>            
            <li>
                <h4>Get Transaction Reports :</h4>
                {!! Form::open( ['action' => 'TransactionApi@postReport', 'M'] )!!}
                    {!! Form::label('fromDate', 'From Date*') !!}
                    {!! Form::text('fromDate', '1970-12-01') !!}

                    {!! Form::label('toDate', 'To Date*') !!}
                    {!! Form::text('toDate', '2015-12-01') !!}

                    {!! Form::label('merchant', 'Merchant ID') !!}
                    {!! Form::text('merchant', null) !!}

                    {!! Form::label('acquirer', 'Acquirer ID') !!}
                    {!! Form::text('acquirer', null) !!}
                    <br>
                    {!! Form::submit('Show') !!}

                {!! Form::close()!!} 

            </li>
            <li>User Update</li>
            <li>User info</li>
            <li>User </li>
        </ul>


    </body>
</html>
