@extends('layouts.app')

@section('content')
<div class="container">
  @if(session()->has('error_status'))
    <div class="alert alert-warning text-center" role="alert">
      {{ session('error_status') }}
    </div>
  @endif
    <div class="row justify-content-center" id="login_content">
      <div class="col-md-8">
        <div class="card">
          {{-- form title --}}
          <div class="card-header">
              <h3 class="card-title">{{ __('Login') }}</h3>
          </div>

          <div class="card-body">
            {{-- form for login --}}
            <form method="POST" action="{{ route('login.postLogin') }}">
              @csrf
              {{-- email input and return error message --}}
              <div class="form-group row">
                <label for="staff_number" class="col-md-4 col-form-label text-md-right">User Number</label>
                <div class="col-md-6">
                    <input id="staff_number" type="text" class="form-control @error('staff_number') is-invalid @enderror" name="staff_number" value="{{ old('staff_number') }}" required autocomplete="staff_number" autofocus>
                    @error('staff_number')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>

              {{-- password input and return error message --}}
              <div class="form-group row">
                <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>
                <div class="col-md-6">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>

              {{-- login button --}}
              <div class="form-group row mb-0">
                <div class="col-md-8 offset-md-4">
                  <button type="submit" class="btn btn-primary">
                      {{ __('Login') }}
                  </button>
                  {{-- forgot password --}}
                  @if (Route::has('password.request'))
                      <a class="btn btn-link" href="{{ route('password.request') }}">
                          {{ __('Forgot Your Password?') }}
                      </a>
                  @endif
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
</div>

@endsection
