@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                </div>
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

    <div class="row">
        <div class="col-md-4 order-md-2 mb-4">
            <h4 class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">Your Proudcts</span>
                <span class="badge badge-secondary badge-pill"> {{ Auth::user()->products->count()}}</span>
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
                        </div>
                        <span class="text-muted">{{$product->price}}</span>
                    </li>
                @endforeach
                <li class="list-group-item d-flex justify-content-between">
                    <span>Total </span>
                    <strong>500</strong>
                </li>
            </ul>

            <form class="card p-2" method="post" action="{{url('/refresh')}}">
                @csrf
                <div class="input-group">
                    <button type="submit" style="width: 331px;" class="btn btn-success">Refresh</button>
                </div>
            </form>
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
                    <div class="custom-control custom-radio">
                        <input id="debit" name="paymentMethod" value="amazon" type="radio" class="custom-control-input" required>
                        <label class="custom-control-label" for="debit">Amazon</label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input id="paypal" name="paymentMethod" type="radio" value="jumia" class="custom-control-input" required>
                        <label class="custom-control-label" for="paypal">Jumia</label>
                    </div>
                </div>
                <button class="btn btn-primary btn-lg btn-block" type="submit">Continue to checkout</button>
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
