<div class="content-page">
    <br>
    <!-- Start content -->
    <div class="content">
        <div class="container">
            @if(Session::has('success'))
            <div class="alert alert-success" role="alert">
                <strong>Success : </strong>{{Session::get('success')}}
            </div>

            @elseif(Session::has('error'))
            <div class="alert alert-danger" role="alert">
                <strong>Alert : </strong>{{Session::get('error')}}
            </div>
            @elseif(Session::has('warning'))
                <div class="alert alert-warning" role="alert">
                    <strong>Warning : </strong>{{Session::get('warning')}}
                </div>
            @endif
            @if(count($errors) > 0)
                <div class="alert alert-danger", role="alert">
                    <Strong>Error</Strong>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>
                                {{$error}}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>

</div>