<!DOCTYPE html>
<html>
    <head>
        <title>Uygulama - Transaction Report</title>

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
             
        {{-- Errors --}}
        @if( ! $errors->isEmpty() )
            <h5 class="spanInfo">Errors:</h5>
            <ul>
                @foreach($errors->all() as $msg)
                     <span class="spanInfo">{{$msg}}</span>
                @endforeach
            </ul>
           
        @endif

        <h3>Reports</h3>
         
        @if( ! $items->isEmpty())
        <table class="w3-table-all" style="width:300px">
            <tbody><tr>
                <th>#</th>
                <th>Currency</th>
                <th>Count</th>
                <th>Total</th>      
                
            </tr>
            {{-- List Items--}}
            @foreach($items as $key => $item)
            <tr>
                <td>{{$key}}</td>
                <td>{{$item->currency}}</td>
                <td>{{$item->count}}</td>        
                <td>{{$item->total}}</td>
            </tr>
            @endforeach
            
            </tbody>
        </table>
        {{-- If there is no result --}}
        @else
         <span class="spanInfo">No Report !</span>
        @endif


    </body>
</html>
