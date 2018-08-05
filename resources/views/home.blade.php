@extends('layouts.app')

@section('css')
    <style>
    .switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
    }

    .switch input {display:none;}

    .slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    -webkit-transition: .4s;
    transition: .4s;
    }

    .slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;
    }

    input:checked + .slider {
    background-color: #2196F3;
    }

    input:focus + .slider {
    box-shadow: 0 0 1px #2196F3;
    }

    input:checked + .slider:before {
    -webkit-transform: translateX(26px);
    -ms-transform: translateX(26px);
    transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
    border-radius: 34px;
    }

    .slider.round:before {
    border-radius: 50%;
    }
    </style>
@endsection


@section('content')
<div class="container">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    Welcome To your dashboard add and track website's items
                </div>
            </div>
        </div>
</div>
<div class="container">
    <div class="py-5 text-center">
        <img class="d-block mx-auto mb-4" src="{{asset('img/home.png')}}" alt="" width="72" height="72">
        <h2>Web Scraping</h2>
        <p class="lead">Below is a Demo made for web-scraping you can scrap the Amount of the products by providing the link of the product from earthier <span class="badge badge-primary badge-pill"> Souq</span> <span class="badge badge-success badge-pill"> Amazon</span> <span class="badge badge-warning badge-pill"> Jumia</span></p>
    </div>

    {{--@if(Auth::user()->products->count() > 0)--}}
    {{--<div class="container">--}}
        {{--<div class="row">--}}
            {{--<div class="col-sm-6 col-md-12">--}}
                {{--<div class="alert alert-info">--}}
                    {{--<button type="button" class="close" data-dismiss="alert" aria-hidden="true">--}}
                        {{--×</button>--}}
                    {{--<strong>Auto Pilot</strong>--}}
                    {{--<hr class="message-inner-separator">--}}
                    {{--<p>--}}
                        {{--All Your Product/s are up to date keep Adding New Products</p>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div><br><br>--}}
    {{--@endif--}}


    <div class="row">
        <div class="col-md-4 order-md-2 mb-4">
            <h4 class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">Your Products</span>
                {{--<div class="loader"></div>--}}

                <span class="badge badge-primary badge-pill"> {{ Auth::user()->products->count()}}</span>
            </h4>
            <ul class="list-group mb-3">
                @foreach(Auth::user()->products as $product)
                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                        <div>
                            <a href="{{$product->link}}">
                                <h6 class="my-0">{{$product->name}}</h6>
                            </a>
                            <small class="text-muted">{{$product->amount}}</small>
                            <br>
                            <small class="text-muted">last refresh: {{$product->updated_at->diffForHumans()}}</small>
                            <br>
                            <div class="row">
                                <div class="col-md-2">
                                    @if($product->type =='souq')
                                        <span class="badge badge-primary "> Souq</span>
                                    @elseif($product->type =='amazon')
                                        <span class="badge badge-success "> Amazon</span>
                                    @else
                                        <span class="badge badge-danger "> not found</span>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <a href="{{url('/delete/'.$product->id)}}" style="width: 90px; margin-left: 152px;" class="btn btn-danger">Delete</a>
                                </div>
                            </div>
                        </div>
                        <span class="text-muted">{{$product->price}}</span>
                    </li>
                @endforeach
            </ul>

            <form class="card p-2" method="post" action="{{url('/refresh')}}">
                @csrf
                <div class="input-group">
                    <button type="submit" style="width: 331px;" class="btn btn-success">Refresh</button>
                </div>
            </form>

            {{--<h4 style="margin-top: 20px;" class="d-flex justify-content-between align-items-center mb-3">--}}
                {{--<span class="text-muted">auto pilot</span>--}}
                {{--<label class="switch">--}}
                    {{--<input id="auto_pilot" type="checkbox" checked>--}}
                    {{--<span class="slider round"></span>--}}
                {{--</label>--}}

            {{--</h4>--}}
            <h4  class="d-flex justify-content-between align-items-center mb-3">
            </h4>

        </div>
        <div class="col-md-8 order-md-1">
            <h4 class="mb-3">Add New Product</h4>
            <form method="post" action="{{url('/add')}}" >
                @csrf
                <div class="mb-3">
                    <label for="username">Product Link</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">!</span>
                        </div>
                        <input type="text" class="form-control" id="link" name="link" placeholder="link" required>
                    </div>
                </div>
                <h4 class="mb-3">Web Site</h4>

                <div class="d-block my-3">
                    <div class="custom-control custom-radio">
                        <input id="credit" value="souq" name="paymentMethod" type="radio" class="custom-control-input" checked required>
                        <label class="custom-control-label" for="credit">Souq</label>
                    </div>
                    {{--<div class="custom-control custom-radio">--}}
                        {{--<input id="debit" name="paymentMethod" value="amazon" type="radio" class="custom-control-input" required>--}}
                        {{--<label class="custom-control-label" for="debit">Amazon</label>--}}
                    {{--</div>--}}
                    {{--<div class="custom-control custom-radio">--}}
                        {{--<input id="paypal" name="paymentMethod" type="radio" value="jumia" class="custom-control-input" required>--}}
                        {{--<label class="custom-control-label" for="paypal">Jumia</label>--}}
                    {{--</div>--}}
                </div>
                <button class="btn btn-primary btn-lg btn-block" type="submit">Add Product</button>
            </form>
        </div>
    </div>

    <footer class="my-5 pt-5 text-muted text-center text-small">
        <p class="mb-1">&copy; 2017-2018 MAXES-EG</p>
        <ul class="list-inline">
            <li class="list-inline-item"><a href="#">Privacy</a></li>
            <li class="list-inline-item"><a href="#">Terms</a></li>
            <li class="list-inline-item"><a href="#">Support</a></li>
        </ul>
    </footer>
</div>
@endsection


@section('js')





    <script type="text/javascript">
        function myFunction () {
            console.log('Executed!');
            {{--$.ajax({--}}
                {{--type: "POST",--}}
                {{--url:  '{{route('home.refresh')}}',--}}
                {{--data: {"_token": '{{ csrf_token() }}' },--}}
                {{--dataType:'JSON',--}}

                {{--success: function(data)--}}
                {{--{--}}
{{--//                    var oldView = $("#noviews-"+supplier_id).html();--}}
{{--//--}}
{{--//                    var newView = parseInt(oldView) + 1;--}}
{{--//--}}
{{--//                    $("#noviews-"+supplier_id).html(newView);--}}


                {{--},--}}
                {{--error: function(XMLHttpRequest, textStatus, errorThrown) {--}}
                    {{--alert("some error");--}}
                {{--}--}}
            {{--});--}}
        }

        var interval = setInterval(function () { myFunction(); }, 6000);
    </script>

@endsection
