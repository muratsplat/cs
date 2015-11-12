        {{--  Check Login --}}
        @if(Auth::check())
        
            <h1>You are log in System !</h1>
            <h2> User: <a href="#" alt="User Details">{{$user->email}}</a> </h2>
        
        @endif
