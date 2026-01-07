<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Askbootstrap">
    <meta name="csrf-token" content="ccQDxgjuDcu4TE2En6tSm5n5wYKFivDQ5lLbG1tw">
    <meta name="author" content="Askbootstrap">
    <link rel="icon" type="image/png" href="https://www.foodyari.com/public/assets/images/icons/foodYariLogo.png">
    <title>Foodyari | User | Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!-- Bootstrap core CSS -->
    <link href="https://www.foodyari.com/public/assets/user/vendor/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="https://www.foodyari.com/public/assets/vendor/toastr/toastr.min.css">

</head>
<style>
    body {
        background: #ffffff1f;
        color: #666666;
        font-family: "RobotoDraft", "Roboto", sans-serif;
        font-size: 14px;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    .text-primary{
        color: #ff810a !important;
    }
    .card {
        border: none !important; /* Remove the border */
        --bs-card-border-color: transparent; /* Optional: Override the variable */
    }
    .bg-image {
        /* background: #ffffff url("https://www.foodyari.com/public/assets/user/img/login-background.jpeg") center center / cover no-repeat fixed; */
        background-color: #ff810a;
        filter: blur(8px);
        -webkit-filter: blur(2px);
        height: 100vh;
        width: 100vw;
        position: fixed;
        z-index: -1;
    }

    /* Pen Title */
    .pen-title {
        /* padding: 50px 0; */
        text-align: center;
        letter-spacing: 2px;
    }

    .pen-title h1 {
        margin: 0 0 20px;
        font-size: 48px;
        font-weight: 300;
    }

    .pen-title span {
        font-size: 12px;
    }

    .pen-title span .fa {
        color: #fe6601;
    }

    .pen-title span a {
        color: #fe6601;
        font-weight: 600;
        text-decoration: none;
    }

    /* Form Module */
    .form-module {
        padding: 40px 35px;
        border-radius: 7px;
        position: relative;
        background: #fff;
        max-width: 425px;
        width: 100%;
        /* border-top: 5px solid #fe6601; */
        box-shadow: 0 0 3px rgba(0, 0, 0, 0.1);
        margin: 0 auto;
    }

    .form-module .toggle {
        cursor: pointer;
        position: absolute;
        top: 0;
        right: 0;
        background: #fe6601;
        width: 37%;
        height: 49px;
        margin: -5px 0 0;
        color: #ffffff;
        font-size: 12px;
        line-height: 30px;
        text-align: center;
    }

    .form-module button {
        cursor: pointer;
        background: #fe6601;
        width: 100%;
        border: 0;
        padding: 10px 15px;
        color: #ffffff;
        transition: 0.3s ease;
    }

    .form-module .toggle .tooltip {
        position: absolute;
        top: 5px;
        right: -65px;
        display: block;
        background: rgba(0, 0, 0, 0.006);
        width: auto;
        padding: 5px;
        font-size: 10px;
        line-height: 1;
        text-transform: uppercase;
    }

    .form-module .toggle .tooltip:before {
        content: "";
        position: absolute;
        top: 5px;
        left: -5px;
        display: block;
        border-top: 5px solid transparent;
        border-bottom: 5px solid transparent;
        border-right: 5px solid rgba(0, 0, 0, 0.6);
    }

    .form-module .form {
        display: none;
        padding: 40px;
    }

    .form-module .form:nth-child(2) {
        display: block;
    }

    .form-module label {
        color: #ffffff;
    }

    .form-module h2 {
        margin: 0 0 20px;
        color: #ffffff;
        font-size: 18px;
        font-weight: 400;
        line-height: 1;
    }

    .form-module input {
        outline: none;
        display: block;
        width: 100%;
        border: 1px solid #d9d9d9;
        /* margin: 0 0 20px; */
        padding: 10px 15px;
        box-sizing: border-box;
        font-wieght: 400;
        transition: 0.3s ease;
    }

    .form-module input:focus {
        border: 1px solid #fe6601;
        color: #333333;
    }

    .form-module button {
        cursor: pointer;
        background: #fe6601;
        width: 100%;
        border: 0;
        font-size: 18px;
        padding: 10px 15px;
        color: #ffffff;
        transition: 0.3s ease;
    }
    .btn:hover{
        background: #d55907 !important;
    }

    /* .form-module button:hover {
  background: #f47723;
} */
    .form-module .cta {
        background: #fe6601;
        width: 100%;
        padding: 15px 40px;
        box-sizing: border-box;
        color: #ffffff;
        font-size: 18px;
        text-align: center;
    }

    .form-module .cta a {
        color: #ffffff;
        text-decoration: none;
    }

    .auth-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        min-width: 100%;
        min-height: 100vh;
        background: #ff810a;
    }

    .logo-desc {
        height: 160px;
        width: 160px;
    }

    @media (max-width: 575px) {
        .logo-desc {
            height: 100px;
            width: 100px;
        }

        .auth-wrapper {
            padding: 10px;
        }
    }

    input[type="number"] {
        -moz-appearance: textfield;
    }

    /* Remove arrows in Chrome, Safari, Edge, and Opera */
    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type="number"]::placeholder {
        color: rgb(208, 207, 207);
    }

    .form-control:focus {
        color: #293240;
        background-color: #ffffff;
        border-color: var(--bs-primary);
        outline: 0;
        box-shadow: none;
    }

    .form-control:focus {
        color: #293240;
        background-color: #ffffff;
        border-color: #fe6601 !important;
        outline: 0;
        box-shadow: none;
    }

    .icon-layer {
        color: grey;
    }
</style>

<body>
    <div class="auth-wrapper">
        <div class="module form-module">
            {{-- <div id="form-container">
                <div class="pen-title mx-auto mb-5">
                    <img src="https://www.foodyari.com/public/assets/images/icons/foodYariLogo.png" alt="logo"
                        class="logo-desc">
                </div>
                <h4 class="text-center">Welcome back! Log in to your account</h4>
                <form method="POST" action="javascript:void(0)" id="checkUser">
                    <input type="hidden" name="_token" value="ccQDxgjuDcu4TE2En6tSm5n5wYKFivDQ5lLbG1tw"
                        autocomplete="off"> <input type="hidden" name="fcm_token" value="" id="myFCM_token">
                    <div class="input-group mb-3">
                        <span class="input-group-text " style="height: 45px;">
                            <i class="fas fa-phone icon-layer"></i>
                        </span>
                        <input type="number" name="phone" class="form-control" maxlength="10" placeholder="Phone"
                            required="">
                    </div>
                    <button type="submit">Continue</button>
                </form>
            </div> --}}
            <!-- filepath: h:\xampp\htdocs\foodyari_live\resources\views\vendor-views\auth\login.blade.php -->
            <div class="card">
                <div class="text-center">
                    <div class="card-body p-0" style="border: none !important;">
                        <div class="col-8 mx-auto">
                            <img src="{{asset('assets/images/icons/foodyari.logo.jpg')}}"
                                alt="" class="img-fluid mb-4" style="height: 200px;">
                        </div>
                        <h4 class="mb-3 f-w-400">Welcome back! Log in to your account</h4>

                        <!-- Check User Form -->
                        <div class="auth_form" id="check_user_exist_form" style="display: block">
                            <form action="" method="POST" class="ajax-submit" data-return="" data-reload="function"
                                data-callback="afterCheckPhone" data-alert="true" data-toast="false" data-error="false">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="send_otp" value="1">
                                <div class="input-group mb-3">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    <input type="number" name="phone" class="form-control arrow-none check_auth_phone"
                                        maxlength="10" placeholder="Phone" required>
                                </div>
                                <button class="btn btn-block w-100 btn-primary mt-4 text-uppercase fw-bold"
                                    type="submit">Continue Next</button>
                            </form>
                        </div>

                        <!-- Login Form -->
                        <div class="auth_form" id="user_login_form" style="display: none">
                            <form action="https://www.roomyari.com/login" method="POST" class="ajax-submit"
                                data-return="Logged in Successfully" data-reload="reload">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="mb-3">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        <input type="email" name="phone" class="form-control arrow-none auth_phone"
                                            readonly placeholder="Phone">
                                    </div>
                                    <div class="text-end">
                                        <span role="button" class="small text-primary change_phone">Change Phone</span>
                                    </div>
                                </div>
                                <div class="mb-3" id="login_otp">
                                    <input type="password" name="otp" class="form-control text-center font-18"
                                        maxlength="6" placeholder="######" style="letter-spacing: 16px" required>
                                    <div class="text-end text-primary">
                                        <span role="button" class="small text-primary" id="login_method_password">Login
                                            with Password</span>
                                    </div>
                                </div>
                                <div class="mb-3" id="login_password" style="display: none">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input type="password" name="password" class="form-control"
                                            placeholder="Password">
                                    </div>
                                    <div class="d-flex justify-content-between mt-2">
                                        <span role="button" class="small text-primary" id="forgot_password_method_otp">Forgot Password?</span>
                                        <span role="button" class="small text-primary" id="login_method_otp">Login with
                                            Otp</span>
                                    </div>
                                </div>
                                <div class="mb-3" id="forgot_password" style="display: none">
                                    <div class="mb-3" id="login_otp">
                                        <input type="password" name="forgot_password_otp" class="form-control text-center font-18"
                                            maxlength="6" placeholder="######" style="letter-spacing: 16px" required>
                                    </div>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input type="password" name="password" class="form-control"
                                            placeholder="Password">
                                    </div>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input type="password" name="confirm_password" class="form-control"
                                            placeholder="Confirm Password">
                                    </div>
                                </div>
                                <button class="btn btn-block w-100 btn-primary text-uppercase fw-bold"
                                    type="submit">Secure Login</button>
                            </form>
                        </div>

                        <!-- Registration Form -->
                        <div class="auth_form" id="user_register_form" style="display: none">
                            <form action="https://www.roomyari.com/registration" method="POST" class="ajax-submit"
                                data-return="Registered Successfully" data-reload="reload">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="mb-3">
                                    <div class="input-group">
                                        <span class="input-group-text"><i
                                                class="material-symbols-outlined">call</i></span>
                                        <input type="number" name="phone" class="form-control arrow-none auth_phone"
                                            readonly placeholder="Phone">
                                    </div>
                                    <div class="text-right">
                                        <span role="button" class="small text-primary change_phone">Change Phone</span>
                                    </div>
                                </div>
                                <div class="mb-3" id="register_otp" style="display: none">
                                    <input type="password" name="otp" class="form-control text-center font-18"
                                        maxlength="6" placeholder="######" style="letter-spacing: 16px">
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text"><i
                                            class="material-symbols-outlined">person</i></span>
                                    <input type="text" name="name" class="form-control" placeholder="Name" required>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text"><i class="material-symbols-outlined">mail</i></span>
                                    <input type="email" name="email" class="form-control"
                                        placeholder="Email (optional)">
                                </div>
                                <div class="input-group mb-2">
                                    <span class="input-group-text"><i
                                            class="material-symbols-outlined">password</i></span>
                                    <input type="password" name="password" class="form-control"
                                        placeholder="Create Password">
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text"><i
                                            class="material-symbols-outlined">password</i></span>
                                    <input type="password" name="password_confirmation" class="form-control"
                                        placeholder="Confirm Password">
                                </div>
                                <button class="btn btn-block w-100 btn-primary text-uppercase fw-bold"
                                    type="submit">Register and continue</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>


    <script type="text/javascript"
        src="https://www.foodyari.com/public/assets/user/vendor/jquery/jquery.min.js"></script>
    <script type="text/javascript"
        src="https://www.foodyari.com/public/assets/user/vendor/bootstrap/js/bootstrap.bundle.js"></script>
    <script src="https://www.foodyari.com/public/assets/vendor/toastr/toastr.min.js"></script>
    <script src="https://www.foodyari.com/public/firebase/index.js" type="module"></script>
    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/js/all.min.js"></script>
    {{-- <script>
        $(function () {
            // Switch to OTP login
            $('#login_method_otp').on('click', function () {
                $('#login_otp').show().find('input').attr('required', true);
                $('#login_password').hide().find('input').attr('required', false).val('');
            });

            // Switch to password login
            $('#login_method_password').on('click', function () {
                $('#login_password').show().find('input').attr('required', true);
                $('#login_otp').hide().find('input').attr('required', false).val('');
            });

            // Change phone number
            $('.change_phone').on('click', function () {
                $('.auth_form').hide();
                $('#check_user_exist_form').show();
            });
        });
    </script> --}}



    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Add click event listener to the "Continue Next" button
            document.querySelector('.btn-primary.mt-4').addEventListener('click', function (event) {
                event.preventDefault(); // Prevent form submission

                // Hide the "Check User Form"
                document.getElementById('check_user_exist_form').style.display = 'none';

                // Show the "Login Form"
                document.getElementById('user_login_form').style.display = 'block';
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Show password input field and hide OTP input field
            document.getElementById('login_method_password').addEventListener('click', function () {
                document.getElementById('login_password').style.display = 'block';
                document.getElementById('login_otp').style.display = 'none';
                document.querySelector('#login_password input').setAttribute('required', true);
                document.querySelector('#login_otp input').removeAttribute('required');
            });

            // Show OTP input field and hide password input field
            document.getElementById('login_method_otp').addEventListener('click', function () {
                document.getElementById('login_otp').style.display = 'block';
                document.getElementById('login_password').style.display = 'none';
                document.querySelector('#login_otp input').setAttribute('required', true);
                document.querySelector('#login_password input').removeAttribute('required');
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Add click event listener to the "Forgot Password?" button
            document.getElementById('forgot_password_method_otp').addEventListener('click', function () {
                // Show the "Forgot Password" section
                document.getElementById('forgot_password').style.display = 'block';

                // Hide the "Login Password" section
                document.getElementById('login_password').style.display = 'none';
            });
        });
    </script>

    <script>
        /*
         * =================// customer login submission starts//============================
         */


        document.querySelector('#checkUser').addEventListener('submit', async (event) => {
            event.preventDefault();
            try {

                // toastr.info('Please Wait A While');
                const url = "https://foodyari.com/user/user-check";
                const form = event.target;
                const formData = new FormData(form);
                if (!formData) {
                    throw new Error('Please fill the form properly');
                }
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    body: formData
                });
                if (!res.ok) {
                    const error = await res.json();
                    throw new Error(error.message);
                    return 0;
                }
                const result = await res.json();
                toastr.warning(result.message);
                document.querySelector('#form-container').innerHTML = result.view;
                const tokenElement = document.querySelector('#myFCM_token');
                if (tokenElement) {
                    tokenElement.value = localStorage.getItem('My_FCM_Token') || 'Token not found';
                }

            } catch (error) {
                toastr.error(error.message);

            }
        })
    </script>

</body>

</html>
