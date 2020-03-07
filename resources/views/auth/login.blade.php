@extends('layouts.app')

@section('assets')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')
    <section class="hero is-success is-fullheight">
        <div class="hero-body">
            <div class="container has-text-centered">
                <div class="column is-4 is-offset-4">
                    <div class="box">
                        <figure class="avatar">
                            <img src="{{ asset('images/login.png') }}">
                        </figure>
                        <form class="login-form" method="POST" action="{{ route('login') }}">
                            {{ csrf_field() }}
                            
                            <div class="field">
                                <div class="control">
                                    <input class="input is-large"
                                        id="email" type="email"
                                        name="email" value="{{ old('email') }}"
                                        placeholder="Your email"
                                        required autofocus>
                                </div>
                                @if ($errors->has('email'))
                                    <p class="help is-danger">
                                        {{ $errors->first('email') }}
                                    </p>
                                @endif
                            </div>

                            <div class="field">
                                <div class="control">
                                    <input class="input is-large"
                                        id="password" type="password"
                                        name="password" placeholder="Your password">
                                </div>
                                @if ($errors->has('password'))
                                    <p class="help is-danger">
                                        {{ $errors->first('password') }}
                                    </p>
                                @endif
                            </div>

                            <button class="button is-block is-info is-large is-fullwidth" type="submit">
                                <span class="icon">
                                    <i class="fas fa-sign-in-alt" aria-hidden="true"></i>
                                </span>
                                <span>Login</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
