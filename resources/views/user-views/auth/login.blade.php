<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Askbootstrap">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="Askbootstrap">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/icons/foodYariLogo.png') }}">
    <title>Foodyari | User | Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!-- Bootstrap core CSS -->
    <link href="{{ asset('assets/user/vendor/bootstrap/css/bootstrap.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/vendor/toastr/toastr.min.css') }}">

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

    .bg-image {
        /* background: #ffffff url("{{ asset('assets/user/img/login-background.jpeg') }}") center center / cover no-repeat fixed; */
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
        border-radius: 3px;
        position: relative;
        background: #fff;
        max-width: 400px;
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
        margin: 0 0 20px;
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
    .icon-layer{
        color: grey;
    }
</style>

<body>
    <div class="auth-wrapper">
        <div class="module form-module">
            <div id="form-container">
                <div class="pen-title mx-auto mb-5">
                    <img src="{{ asset('assets/images/icons/foodYariLogo.png') }}" alt="logo" class="logo-desc">
                </div>
                <h4 class="text-center">Welcome back! Log in to your account</h4>
                <form method="POST" action="javascript:void(0)" id="checkUser">
                    @csrf
                    <input type="hidden" name="fcm_token" value="" id="myFCM_token">
                    <div class="input-group mb-3">
                        <span class="input-group-text " style="height: 45px;">
                            <i class="fas fa-phone icon-layer"></i>
                        </span>
                        <input type="number" name="phone" class="form-control" maxlength="10" placeholder="Phone" required="">
                    </div>
                    <button type="submit">Continue</button>
                </form>
             </div>
        </div>
    </div>


    <script type="text/javascript" src="{{ asset('assets/user/vendor/jquery/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/user/vendor/bootstrap/js/bootstrap.bundle.js') }}"></script>
    <script src="{{ asset('assets/vendor/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('firebase/index.js') }}" type="module"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/js/all.min.js"></script>



    @if (Session::has('success'))
        <script>
            toastr.success('{{ Session::get('success') }}');
        </script>
    @endif
    @if (Session::has('info'))
        <script>
            toastr.info('{{ Session::get('info') }}');
        </script>
    @endif

    @if (Session::has('error'))
        <script>
            toastr.info('{{ Session::get('error') }}');
        </script>
    @endif
    @if (Session::has('warning'))
        <script>
            toastr.warning('{{ Session::get('warning') }}');
        </script>
    @endif


    <script>
        /*
         * =================// customer login submission starts//============================
         */


        document.querySelector('#checkUser').addEventListener('submit', async (event) => {
            event.preventDefault();
            try {

                // toastr.info('Please Wait A While');
                const url = "{{ route('user.auth.user-check')}}";
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
