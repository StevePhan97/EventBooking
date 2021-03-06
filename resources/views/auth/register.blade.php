@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right"> Registered as
                            </label>
                            <label class="col-md-2 col-form-label text-md-right">
                                <input type="radio" name="role" value="0"/> Manager
                            </label>
                            <label class="col-md-2 col-form-label text-md-right">
                                <input type="radio" name="role" value="1"/> Host
                            </label>
                            <label class="col-md-2 col-form-label text-md-right">
                                <input type="radio" name="role" value="2"/> Undergrad
                            </label>
                            <label class="col-md-2 col-form-label text-md-right">
                                <input type="radio" name="role" value="3"/> Postgrad
                            </label>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right"> Sex
                            </label>
                            <label class="col-md-3 col-form-label text-md-right">
                                <select name="sex" id="sex" class="form-control">
                                    <option value="">Not specify</option>
                                    <option value="0"> Male</option>
                                    <option value="1"> Female</option>
                                </select>
                            </label>
                            <label class="col-md-2 col-form-label text-md-right">
                                Age
                            </label>
                            <label class="col-md-2 col-form-label text-md-right">
                                <input class="form-control" type="text" id="age" name="age"/>
                            </label>
                        </div>
                        <div class ="form-group row">
                            <label class="col-md-4 col-form-label text-md-right"> Degree
                            </label>
                            <label class="col-md-3 col-form-label text-md-right">
                                <select name="degree" id="degree" class="form-control">
                                    <option value="">Not specify</option>
                                    <option value="0"> CompSci </option>
                                    <option value="1"> Engineer </option>
                                    <option value="2"> Math </option>
                                    <option value="3"> Biology </option>
                                    <option value="4"> Law</option>
                                    <option value="5"> Business </option>
                                    <option value="6"> Language</option>
                                    <option value="7">Media</option>
                                    <option value = "8"> Arts </option>
                                </select>
                            </label>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4" >
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
