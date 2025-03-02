<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grace Technology - Inventory System</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/auth.css')}}">

    @vite(["resources/sass/app.scss"])
</head>

<body>
    <div id="auth">

        <div class="row h-100">
            <div class="col-lg-5 col-12">
                <div id="auth-left">
                    {{-- <div class="auth-logo">
                        <a href="#"><img src="images/logo.png" alt="Logo" style="width: 150px; height: 150px;"></a>
                    </div> --}}
                    <h1 class="auth-title">Log in</h1>
                    {{-- <p class="auth-subtitle mb-5">Log in with your data that you entered during registration.</p> --}}

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input id="email" type="email" class="form-control form-control-xl @error('email') is-invalid @enderror" 
                            name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email login" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            {{-- <input type="text" class="form-control form-control-xl" placeholder="Username"> --}}
                            <div class="form-control-icon">
                                <i class="bi bi-person"></i>
                            </div>
                        </div>
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input id="password" type="password" class="form-control form-control-xl @error('password') is-invalid @enderror" 
                            name="password" required autocomplete="current-password" placeholder="Password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            {{-- <input type="password" class="form-control form-control-xl" placeholder="Password"> --}}
                            <div class="form-control-icon">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                        </div>
                        <div class="form-check form-check-lg d-flex align-items-end">
                            <input class="form-check-input me-2" type="checkbox" value="" id="flexCheckDefault">
                            <label class="form-check-label text-gray-600" for="flexCheckDefault">
                                Keep me logged in
                            </label>
                        </div>
                        <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Log in</button>
                    </form>
                    <div class="text-center mt-5 text-lg fs-4">
                        {{-- <p class="text-gray-600">Don't have an account? <a href="auth-register.html"
                                class="font-bold">Sign
                                up</a>.</p> --}}
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>
                        <p>
                            @if (Route::has('password.request'))
                                <a class="font-bold" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            @endif
                        </p>
                        <p class="auth-subtitle mb-5">Grace Technology &copy; 2025</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 d-none d-lg-block">
                <div id="auth-right">
                    <div class="row mb-5">
                    </div>
                    <div class="row">
                        <div class="col-12 mt-5 text-center">
                            <div class="auth-logo">
                                <img src="images/logo.png" alt="Logo" style="width: 450px; height: 450px;">
                            </div>
                            <p>
                                <strong><h1 class="auth-title">Stock Management System</h1></strong>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</body>

</html>