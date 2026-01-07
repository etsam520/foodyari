<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Askbootstrap">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="Askbootstrap">
    <link rel="icon" type="image/png" href="{{asset('assets/images/icons/foodYariLogo.png')}}">
    <title>Foodyari | User | Delivery Man</title>

    <!-- Bootstrap core CSS -->
    <link href="{{asset('assets/user/vendor/bootstrap/css/bootstrap.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('assets/vendor/toastr/toastr.min.css')}}">

</head>
<style>
  body {
    background: #ff810a;
  color: #666666;
  font-family: "RobotoDraft", "Roboto", sans-serif;
  font-size: 14px;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}

/* .auth-wrapper {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    min-width: 100%;
    min-height: 100vh;

} */

/* Pen Title */
.pen-title {
  padding: 50px 0;
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
    position: relative;
    background: #fff;
    max-width: 500px;
    width: 100%;
    border-top: 5px solid #fe6601;
    box-shadow: 0 0 3px rgba(0, 0, 0, 0.1);
    margin: 0 auto;
}

.form-module .toggle {
    cursor: pointer;
    position: absolute;

    top: 0;
    right: 0;
    background: #fe6601;
    border: 2px solid #fff;
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
.form-module label{
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
    border :2px solid #fff;
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
</style>

<body>
  <div class="auth-wrapper"></div>
   <div class="pen-title mx-auto mb-0">
            <img src="{{asset('assets/images/icons/foodYariLogo.png')}}" alt="logo" style="width: 100px;border-radius: 50%;filter: drop-shadow(0px 0px 10px #ffffff);">
    </div>
    <!-- Form Module-->
    <div class="module form-module ">
      <div class="toggle">
            <a href="{{route('join-as.deliveryman')}}" class="text-white w-100 login h5 "><img  src="{{asset('assets/user/img/icons/login-user.svg')}}" alt="">Sign Up</a>
        <div class="tooltip">Click Me</div>
      </div>
      <div class="form">
        <h2>Login to your account</h2>
        <form method="POST" action="{{route('deliveryman.auth.login-submit')}}">
         @csrf
          <input type="email" name="email" required placeholder="Email"/>
          <input type="password" name="password" required placeholder="Password"/>
          <button type="submit">Login</button>
          <span><a href="{{route('deliveryman.auth.forgot-password')}}" style="float: right">Forgot Password?</a></span>
        </form>
      </div>
      {{-- <div class="cta"><a href="javascript:void(0)">Forgot your password?</a></div> --}}
    </div>

    <script type="text/javascript" src="{{asset('assets/user/vendor/jquery/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/user/vendor/bootstrap/js/bootstrap.bundle.js')}}"></script>
    <script src="{{asset('assets/vendor/toastr/toastr.min.js')}}"></script>
    <script src="{{asset('firebase/index.js')}}" type="module"></script>


    @if(Session::has('success'))
    <script>
        toastr.success('{{ Session::get('success') }}');
    </script>
    @endif
    @if(Session::has('info'))
    <script>
        toastr.info('{{ Session::get('info') }}');
    </script>
    @endif

    @if(Session::has('error'))
    <script>
        toastr.info('{{ Session::get('error') }}');
    </script>
    @endif
    @if(Session::has('warning'))
    <script>
        toastr.warning('{{ Session::get('warning') }}');
    </script>
    @endif
    <script>
        $(document).ready(function() {
            $('select.select-2').select2();
        });
    </script>

</body>

</html>
