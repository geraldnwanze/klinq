<div>
    @if(Session::has('message'))
        <p style="color:green;">{!! Session::get('message') !!}</p>
    @elseif(Session::has('error'))
        <p style="color:red;">{!! Session::get('error') !!}</p>
    @endif

    @if($errors->any())
        @foreach($errors->all() as $error)
            <p style="color:red">{!! $error !!}</p>
        @endforeach
    @endif


</div>
