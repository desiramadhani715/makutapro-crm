{{-- <x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-label for="email" :value="__('Username')" />

                <x-input id="email" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required autofocus />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" :value="__('Password')" />

                <x-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />
            </div>

            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="remember">
                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-button class="ml-3">
                    {{ __('Log in') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout> --}}

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Makutapro</title>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
  </head>

<body style="font-family: 'Montserrat',
sans-serif;">

    <div class="container-fluid  mt-md-5 mt-lg-0" >
        <div class="container pt-0 pt-md-5 pt-lg-5 d-flex align-self-center">
            <div class="row ">
                <div class="col-12 col-md-7 mb-0  mt-5" >
                    {{-- <img src="{{asset('images/makuta-01.png')}}" alt="" class="img-fluid pt-5"> --}}
                    {{-- <lottie-player id="firstLottie" src="https://lottiefiles.com/share/1l2kqjrh" style="width:100%; height: auto;"></lottie-player> --}}
                    <!--<lottie-player src="https://assets3.lottiefiles.com/packages/lf20_p2puhbpy.json"  background="transparent"  speed="1"  style="width: 100%; height: auto;"  loop  autoplay></lottie-player>-->

                    <img src="{{asset('Makuta-admin.png')}}" class="w-100 mt-5" alt="">

                </div>
                <div class="col-12 col-md-5 mt-md-5 mt-lg-5 pt-lg-5 pt-0 mt-0 ml-2 ml-lg-0" >
                  <div class="d-inline-flex p-2 bd-highlight mt-lg-5 mt-0" style="border-radius: 30px; background-color: #6F9CD3;">
                    <form action="{{ route('login') }}" method="POST" role="form" style="color: #fff;" class="py-3 mx-2">
                      @csrf
                        <h3 class="mb-3"><strong>Makutapro CRM</strong></h3>
                        <div class="form-group">
                          <div class="row">
                            <div class="col-4 d-flex align-self-center">
                              <label for="">Category</label>
                            </div>
                            <div class="col-8">
                              <select name="category" id="" class="form-select" style="border-radius: 25px;">
                                <option value="">Developer</option>
                                <option value="">Sales Manager</option>
                              </select>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="row my-2">
                            <div class="col-4 d-flex align-self-center">
                              <label for="">Username</label>
                            </div>
                            <div class="col-8">
                              <input class="form-control" id="username" type="text" autocomplete="off" name="username" value="{{ old('username') }}" style="border-radius: 25px;">
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="row my-2">
                            <div class="col-4 d-flex align-self-center">
                              <label for="">Password</label>
                            </div>
                            <div class="col-8">
                              <input class="form-control" id="password" type="password" name="password" style="border-radius: 25px;">
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="row">
                            <div class="col-4 d-flex align-self-center">
                            </div>
                            <div class="col-8">
                              <button type="submit" class="btn d-block w-100" style="background-color: #FB8C2E; color : #fff; border-radius:25px;">Submit</button>
                            </div>
                          </div>
                        </div>

                    </form>
                  </div>
                  <a href="http://makutadesign.com" target="blank"><img src="{{asset('images/makuta-03.png')}}" class="img-fluid mx-auto d-blox mx-lg-0" style="width: 50%;" alt=""></a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>




