@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body text-center">
                    <a href="login/facebook" class="btn btn-primary">
                        <i class="fab fa-circle fa-facebook fa-3x"></i>
                        Entrar com o Facebook
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
