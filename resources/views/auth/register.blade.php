@extends('layouts.app')

@section('content')
<div class="container-fluid pb-2" style="background-image: url(storage/app_photos/Farming.jpg); background-size: cover">
    <div class="row">
        <div class="col-sm-8 my-auto text-white"
            style="font-size: 64px; font-family: Arial,sans-serif; font-weight: 600;">
            <div class="mx-auto text-left">
                Good <span style="color: #ffa500 ">farming</span>.
                Good <span style="color: #00ff2b ">life</span>.
                <p class="h5 text-sm-left">SFAWS is a platform inspired by the way you work. From providing you aid to
                    your passion in handling farms and other Agricultural businesses, you can sell and search for
                    supplies or even consult other farmers, suppliers and identified experts.</p>
            </div>
            <!-- <div class="p-2 bd-highlight">Flex item 3</div> -->
        </div>
        <div class="col-sm-4">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group">
                            <label for="name" class="small">{{ __('Name') }}</label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="type" class="small">{{ __('Chosse User Type') }}</label>
                            <select id="type" class="form-control @error('type') is-invalid @enderror" name="type"
                                value="{{ old('type') }}" required autocomplete="type" autofocus>
                                <option value="Farmer">Farmer</option>
                                <option value="Supplier">Supplier</option>
                            </select>
                            @error('type')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="username" class="small">{{ __('Username') }}</label>
                                <input id="username" type="text"
                                    class="form-control @error('username') is-invalid @enderror" name="username"
                                    value="{{ old('username') }}" required autocomplete="username" autofocus>
                                @error('username')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="small">{{ __('E-Mail Address') }}</label>
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" required autocomplete="email">

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <label for="password"
                                class="col-md-4 col-form-label text-md-right small">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password"
                                    class="form-control form-control-sm @error('password') is-invalid @enderror"
                                    name="password" required autocomplete="new-password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <label for="password-confirm"
                                class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control form-control-sm "
                                    name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success btn-block">
                            {{ __('Sign Up for SFAWS') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
