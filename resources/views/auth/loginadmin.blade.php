
<!doctype html>
<!--
* Tabler - Premium and Open Source dashboard template with responsive and high quality UI.
* @version 1.0.0-beta20
* @link https://tabler.io
* Copyright 2018-2023 The Tabler Authors
* Copyright 2018-2023 codecalm.net Paweł Kuna
* Licensed under MIT (https://github.com/tabler/tabler/blob/master/LICENSE)
-->
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>PT. DJEMOENDO</title>
    <!-- CSS files -->
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logodennis2.png') }}" sizes="32x32">
    <link href="{{ asset ('tabler/dist/css/tabler.min.css?1692870487') }}" rel="stylesheet"/>
    <link href="{{ asset ('tabler/dist/css/tabler-flags.min.css?1692870487') }}" rel="stylesheet"/>
    <link href="{{ asset ('tabler/dist/css/tabler-payments.min.css?1692870487') }}" rel="stylesheet"/>
    <link href="{{ asset ('tabler/dist/css/tabler-vendors.min.css?1692870487') }}" rel="stylesheet"/>
    <link href="{{ asset ('tabler/dist/css/demo.min.css?1692870487') }}" rel="stylesheet"/>
    <style>
      @import url('https://rsms.me/inter/inter.css');
      :root {
      	--tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
      }
      body {
      	font-feature-settings: "cv03", "cv04", "cv11";
        background-image: url('{{ asset('assets/img/kantor.jpg') }}');
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
        overflow: hidden;
      }

      .floating-image {
        animation: float 6s ease-in-out infinite;
      }

      @keyframes float {
        0% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-20px);
        }
        100% {
            transform: translateY(0px);
        }
      }
    </style>
  </head>
  <body  class=" d-flex flex-column">
    <div class="page page-center">
      <div class="container container-normal py-1">
        <div class="row align-items-center g-4">
          <div class="col-lg">
            <div class="container-tight">
              <div class="text-center mb-4">
                <a href="." class="navbar-brand navbar-brand-autodark"><img src="./static/logo.svg" height="36" alt=""></a>
              </div>
              <div class="card card-md" style="transform: scale(0.85);">
                <div class="card-body">
                    <div class="text-center mb-3">
                        <img src="{{ asset('assets/img/logodennis2.png') }}" height="80" alt="logo" class="floating-image">
                    </div>
                  <h2 class="h2 text-center mb-3" style="text-shadow: 2px 2px 4px #ffffff; color: black; font-size: 1.2rem;">PT. DJEMOENDO<br>ATTENDANCE MONITORING</h2>
                  @if (Session::get('warning'))
                      <div class="alert alert-warning">
                            <p>{{ Session::get('warning') }}</p>
                      </div>
                  @endif
                  <form action="/prosesloginadmin" method="post" autocomplete="off" novalidate>
                    @csrf
                    <div class="mb-3">
                      <label class="form-label">Email address</label>
                      <input type="email" name="email" class="form-control" placeholder="your@email.com" autocomplete="off">
                    </div>
                    <div class="mb-2">
                      <label class="form-label">
                        Password
                        <span class="form-label-description">
                          <a href="./forgot-password.html">I forgot password</a>
                        </span>
                      </label>
                      <div class="input-group input-group-flat">
                        <input type="password" name="password" class="form-control"  placeholder="Your password"  autocomplete="off" id="password-input">
                        <span class="input-group-text">
                          <a href="#" class="link-secondary" title="Show password" data-bs-toggle="tooltip" id="show-password-toggle"><!-- Download SVG icon from http://tabler-icons.io/i/eye -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                          </a>
                        </span>
                      </div>
                    </div>
                    <div class="mb-2">
                      <label class="form-check">
                        <input type="checkbox" class="form-check-input"/>
                        <span class="form-check-label">Remember me on this device</span>
                      </label>
                    </div>
                    <div class="form-footer">
                      <button type="submit" class="btn btn-primary w-100">Sign in</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg d-none d-lg-block">
            <img src="{{ asset('tabler/static/illustrations/undraw_secure_login_pdn4.svg')}}" height="300" class="d-block mx-auto floating-image" alt="">
          </div>
        </div>
      </div>
    </div>
    <!-- Libs JS -->
    <!-- Tabler Core -->
    <script src="{{ asset('tabler/dist/js/tabler.min.js?1692870487') }}" defer></script>
    <script src="{{ asset('tabler/dist/js/demo.min.js?1692870487') }}" defer></script>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const passwordInput = document.getElementById('password-input');
        const showPasswordToggle = document.getElementById('show-password-toggle');

        if (showPasswordToggle) {
          showPasswordToggle.addEventListener('click', function (e) {
            e.preventDefault();
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
          });
        }
      });
    </script>
  </body>
</html>