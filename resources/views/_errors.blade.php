        {{-- Errors --}}
        @if( ! $errors->isEmpty() )
            <h5 class="spanInfo">Errors:</h5>
            <ul>
                @foreach($errors->all() as $msg)
                     <span class="spanInfo">{{$msg}}</span>
                @endforeach
            </ul>
           
        @endif