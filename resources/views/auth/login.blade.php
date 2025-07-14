<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#000000">
    <title>PT. Djemoendo Face Attendance</title>
    <meta name="description" content="Aplikasi Absensi Canggih">
    <meta name="keywords" content="bootstrap 4, mobile template, cordova, phonegap, mobile, html" />
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logodennis2.png') }}" sizes="32x32">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/icon/logodennis2.png') }}">
    <!-- Menggunakan style.css bawaan jika ada style dasar yang ingin dipertahankan -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <!-- CSS Kustom untuk halaman login yang baru -->
    <link rel="stylesheet" href="{{ asset('assets/css/custom-login-v2.css') }}">
    <link rel="manifest" href="__manifest.json">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="login-page-body">

    <!-- Latar Belakang Beranimasi dengan Partikel -->
    <div class="background-wrapper">
        <ul class="particles">
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
        </ul>
    </div>

    <!-- loader -->
    <div id="loader">
        <div class="spinner-border text-primary" role="status"></div>
    </div>
    <!-- * loader -->


    <!-- App Capsule -->
    <div id="appCapsule" class="pt-0">

        <div class="login-container">
            <div class="section text-center">
                <!-- Logo Anda dengan animasi mengambang -->
                <div class="login-logo-container">
                    <img src="{{ asset('assets/img/login/login.jpg') }}" alt="Logo Perusahaan" class="logo-image">
                </div>
                
                <!-- Judul Perusahaan yang Cantik -->
                <div class="login-title-wrapper">
                    <h1 class="main-title">PT. DJEMOENDO</h1>
                    <h2 class="sub-title">Face Attendance</h2>
                </div>
            </div>

            <div class="section mt-2">
                <h4 class="login-subtitle">Silahkan masuk untuk melanjutkan</h4>
            </div>

            <div class="section mt-2 mb-3">
                <form id="loginForm" class="login-form-wrapper">
                    @csrf
                    <div class="form-group-custom animated-form-group">
                        <div class="input-wrapper">
                            <ion-icon name="person-outline" class="input-icon"></ion-icon>
                            <input type="text" name="nik" class="form-control" id="nik" placeholder="NIK Karyawan" required>
                        </div>
                    </div>

                    <div class="form-group-custom animated-form-group" style="animation-delay: 0.2s;">
                        <div class="input-wrapper">
                            <ion-icon name="lock-closed-outline" class="input-icon"></ion-icon>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                        </div>
                    </div>

                    <div class="form-links mt-2 animated-form-group" style="animation-delay: 0.3s;">
                        <div><a href="#" id="forgot-password-link" class="text-muted">Lupa Password?</a></div>
                    </div>

                    <div class="form-button-group animated-form-group" style="animation-delay: 0.4s;">
                        <button type="submit" class="btn btn-primary btn-block btn-lg btn-login">
                            <ion-icon name="log-in-outline"></ion-icon>
                            <span class="login-text">MASUK</span>
                            <div class="spinner-border spinner-border-sm login-spinner" role="status" style="display: none;"></div>
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
    <!-- * App Capsule -->

    <!-- ///////////// Js Files ////////////////////  -->
    <script src="{{ asset('assets/js/lib/jquery-3.4.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib/bootstrap.min.js') }}"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="{{ asset('assets/js/plugins/owl-carousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/jquery-circle-progress/circle-progress.min.js') }}"></script>
    <script src="{{ asset('assets/js/base.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Lupa Password
            document.getElementById('forgot-password-link').addEventListener('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Informasi',
                    text: 'Silahkan Hubungi Admin HRD.',
                    icon: 'info',
                    confirmButtonText: 'Baik',
                    confirmButtonColor: '#4c68d7',
                    customClass: {
                        popup: 'swal-popup-custom',
                        title: 'swal-title-custom',
                        htmlContainer: 'swal-text-custom'
                    }
                });
            });

            // Logika Login dengan AJAX
            const loginForm = document.getElementById('loginForm');
            const loginButton = loginForm.querySelector('.btn-login');
            const loginText = loginButton.querySelector('.login-text');
            const loginSpinner = loginButton.querySelector('.login-spinner');

            loginForm.addEventListener('submit', function(e) {
                e.preventDefault();

                loginText.style.display = 'none';
                loginSpinner.style.display = 'block';
                loginButton.disabled = true;

                const formData = new FormData(loginForm);
                const data = Object.fromEntries(formData.entries());

                fetch('/proseslogin', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json' // Memberitahu server kita menerima JSON
                    },
                    body: JSON.stringify(data)
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw { status: response.status, data: err };
                        });
                    }
                    return response.json();
                })
                .then(result => {
                    if (result.status === 'success') {
                        window.location.href = result.redirect_url;
                    } 
                })
                .catch(error => {
                    let errorMessage = 'Terjadi kesalahan. Silakan coba lagi.';
                    
                    if (error && error.data && error.data.message) {
                        errorMessage = error.data.message;
                    } else if (error && error.message) {
                        errorMessage = 'Tidak dapat terhubung ke server. Periksa koneksi Anda.';
                    }
                    console.error('Login Error:', error);

                    Swal.fire({
                        title: 'Gagal',
                        text: errorMessage,
                        icon: 'error',
                        confirmButtonText: 'Coba Lagi',
                        confirmButtonColor: '#d33',
                        customClass: {
                            popup: 'swal-popup-custom',
                            title: 'swal-title-custom',
                            htmlContainer: 'swal-text-custom'
                        }
                    });

                    loginText.style.display = 'block';
                    loginSpinner.style.display = 'none';
                    loginButton.disabled = false;
                });
            });
        });
    </script>
    <style>
        /* Custom style untuk SweetAlert */
        .swal-popup-custom {
            font-family: 'Poppins', sans-serif;
            border-radius: 15px;
        }
        .swal-title-custom {
            font-size: 1.25rem;
        }
        .swal-text-custom {
            font-size: 1rem;
        }

        /* Menghilangkan form-button-group default jika ada dan menambahkan jarak */
        .form-button-group {
            position: static;
            background: transparent;
            padding: 0;
            min-height: auto;
            margin-top: 20px; /* Menambahkan jarak dari link lupa password */
        }
    </style>

</body>

</html>
