@extends('layouts.app')

@section('css')

@endsection


@section('content')

    <main role="main">
        <section class="jumbotron text-center">
                <h1 class="jumbotron-heading">Chan Scan</h1>
                <p class="lead">Below is a Demo made for tracking the products by providing the link of the product from <span class="badge badge-primary badge-pill"> Souq</span><br> To get products last update hit Refresh and all your products will be up to date</p>
                   <div class="container">
                       <form method="post" action="{{url('/add')}}" >
                           @csrf
                           <div class="input-group mb-3">
                               <div class="input-group-prepend">
                                   <label class="input-group-text" for="inputGroupSelect01">Product Link</label>
                               </div>
                               <input type="text" name="link" class="form-control" placeholder="www.souq.com/productx" aria-label="Recipient's username" aria-describedby="button-addon2">
                               <input type="hidden" name="paymentMethod"  value="souq" class="form-control" placeholder="www.souq.com/productx" aria-label="Recipient's username" aria-describedby="button-addon2">

                               <div class="input-group-append">
                                   <button class="btn btn-outline-primary" type="submit" id="button-addon2"><i class="fa fa-search"></i> Scan</button>
                               </div>
                           </div>
                       </form>

                   </div>

        </section>

        @include('layouts.alerts')
        <div class="album py-5 bg-light">
            <div class="container">

                <div class="card">
                    <div class="card-body">
                        <div style="height:27px;" class="d-flex justify-content-between align-items-center">
                            <h3 style="margin-top: 8px;"><span class="badge badge-primary badge-pill">{{ Auth::user()->products->count()}}</span> Product in your List</h3>
                            @if( Auth::user()->products->count() > 0)
                            <a href="{{url('/refresh')}}" class="btn btn-success my-2"><i class="fa fa-refresh"></i> Refresh</a>
                            @endif
                        </div>
                    </div>
                </div>

                <br><br>
                <div class="row">
                    @foreach(Auth::user()->products as $product)
                         <div class="col-md-4">
                        <div class="card mb-4 shadow-sm">
                            <div class="d-flex align-items-center p-3  text-white-50 bg-purple rounded shadow-sm">
                                <a href="https://egypt.souq.com">
                                    <img class="mr-3" src="{{asset('img/souq.jpg')}}" alt="" width="48" height="48">
                                </a>
                                <div class="lh-100">
                                    <h6 class="mb-0 text-white lh-100"><a style="color: whitesmoke" href="https://egypt.souq.com">Souq.com</a></h6>
                                </div>
                            </div>
                            <div class="card-body">
                                <a href="{{$product->link}}">
                                    <p class="card-text" style="height: 80px;">
                                        {{$product->name}} <br>
                                    </p>
                                </a>
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5>
                                        <span class="badge badge-success ">Price:  {{$product->price}}</span>
                                    </h5>
                                    <h5>
                                        <span class="badge badge-warning">{{$product->amount}}</span>
                                    </h5>

                                </div>
                                <small class="text-muted">Last Refresh: {{$product->updated_at->diffForHumans()}}</small>
                                <br>

                                <ul class="list-group">
                                    @foreach($product->amounts as $amount)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{$amount->amount}}
                                        <span class="badge badge-primary badge-pill">Since {{$amount->created_at->diffForHumans()}}</span>
                                    </li>
                                    @endforeach
                                </ul>
                                <br>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="btn-group">
                                        <a href="{{url('/delete/'.$product->id)}}" style="width: 308px;" class="btn btn-outline-danger">Delete</a>
                                    </div>
                                    {{--<small class="text-muted">9 mins</small>--}}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

    </main>

    <footer class="my-5 pt-5 text-muted text-center text-small">
        <div class="container">
            <p class="float-right">
                <a href="#">Back to top</a>
            </p>
            <p class="mb-1">© 2017-2018 Created by <a href="https://maxeseg.com/">Maxeseg.com</a></p>
        </div>
    </footer>

@endsection


@section('js')


@endsection
