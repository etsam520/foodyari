<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Askbootstrap">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="Askbootstrap">
    <link rel="icon" type="image/png" href="{{asset('assets/images/icons/foodYariLogo.png')}}">
    <title>Foodyari | User | Login</title>

    <!-- Bootstrap core CSS -->
    <link href="{{asset('assets/user/vendor/bootstrap/css/bootstrap.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('assets/vendor/toastr/toastr.min.css')}}">
    
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
    /* background: #ffffff url("{{asset('assets/user/img/login-background.jpeg')}}") center center / cover no-repeat fixed; */
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
  <div class="bg-image"></div>
   <div class="pen-title mx-auto mb-0" >
            <img src="{{asset('assets/images/icons/foodYariLogo.png')}}" alt="logo" style="width: 100px;border-radius: 50%;">
    </div>
    <!-- Form Module-->
    <div class="module form-module">
      <div class="toggle">
           <span class="text-white w-100 register h5"><img src="{{asset('assets/user/img/icons/add-user.svg')}}" alt="">Register</span> 
            <span class="text-white w-100 login d-none h5 "><img src="{{asset('assets/user/img/icons/login-user.svg')}}" alt="">Login</span>
        <div class="tooltip">Click Me</div>
      </div>
      <div class="form">
        <h2>Login to your account</h2>
        <form method="POST" action="javascript:void(0)" id="loginForm">
         @csrf
          <input type="text" name="phone" required placeholder="Mobile Number"/>
          {{-- <input type="text" name="otp" required placeholder="OTP"/> --}}
          <input type="hidden" name="fcm_token" value="" id="myFCM_token">
          <button style="width:30%;" type="submit">Submit</button>
        </form>
      </div>
      <div class="form">
        <h2>Create an account</h2>
        <form action="" method="POST" id="registrationForm" enctype="multipart/form-data">
            <label for="avatar">Avatar:</label>
            <input class="form-control" id="avatar" data-validate="true" data-regx="\.(jpe?g|png)$" name="image" data-user-image="image" type="file" accept="image/*" required>
        
            <label for="fname">First Name:</label>
            <input type="text" class="form-control" data-validate="true" data-regx="^[a-zA-Z.0-9 ]{3,50}$" name="f_name" id="fname" placeholder="First Name" required>
        
            <label for="mobno">Mobile Number:</label>
            <input type="text" class="form-control" data-validate="true" data-regx="[0-9]{10}" id="mobno" name="phone" placeholder="Mobile Number" required>
        
            <label for="email">Email:</label>
            <input type="email" class="form-control" data-validate="true" data-regx="^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$" name="email" id="email" placeholder="Email" required>
        
            <label for="add1">Street Address:</label>
            <input type="text" class="form-control" data-validate="true" data-regx="[a-zA-Z]{0,255}" name="street" id="add1" placeholder="Street Address" required>
        
            <label for="pno">Pin Code:</label>
            <input type="text" class="form-control" data-validate="true" data-regx="[0-9]{6}" name="pincode" id="pno" placeholder="Pin Code" required>
        
            <label for="city">Town/City:</label>
            <input type="text" class="form-control" data-validate="true" data-regx="[a-zA-Z]{0,255}" id="city" name="city" placeholder="Town/City" required>
        
            <label for="pass">Password:</label>
            <input type="password" class="form-control" autocomplete name="password" id="pass" placeholder="Password" required>
            <label for="c_pass">Confirt Password:</label>
            <input type="password" class="form-control" autocomplete name="c_password" id="c_password" placeholder="Confirm Password" required>
            
          <button type="submit">Register</button>
        </form>
      </div>
      <div class="cta"><a href="javascript:void(0)">Forgot your password?</a></div>
    </div>
    
    <script type="text/javascript" src="{{asset('assets/user/vendor/jquery/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/user/vendor/bootstrap/js/bootstrap.bundle.js')}}"></script>
    <script src="{{asset('assets/vendor/toastr/toastr.min.js')}}"></script>
    <script src="{{asset('firebase/index.js')}}" type="module"></script>
    <script>
      $('.toggle').click(function(){
      // Switches the Icon
      $(this).children('span.register').toggleClass('d-none');
      $(this).children('span.login').toggleClass('d-none');
      console.log()
      // Switches the forms  
      $('.form').animate({
         height: "toggle",
         'padding-top': 'toggle',
         'padding-bottom': 'toggle',
         opacity: "toggle"
      }, "slow");
      });
    </script>
 
    <script type="module">
      import { validateForm } from "{{asset('assets/js/Helpers/helper.js')}}";
      // cosnt form = 
 /*
* =================// customer form submission starts//============================
*/    
const registraion = document.querySelector('#registrationForm');
registraion.addEventListener('submit', async function(event) {
    event.preventDefault();
    if(validateForm('#registrationForm')){
      try {
        const url = "{{route('createUser')}}";
        const formData = new FormData(registraion);

         if (!formData) {
            throw new Error('Please fill the form properly');
         } else if(formData.get('password') !== formData.get('c_password')) {
            throw new Error('Confirm Password Doesn\'t match with the current Password');
         }
        const res = await fetch(url, {
            method: 'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            body: formData
        });
        if (!res.ok) {
            const errorMessage = await res.json(); 
            throw new Error(handleError(errorMessage)); 
        }
        const result = await res.json();
        // console.log(result);
        if(result.success){
            toastr.success(result.success);
            registraion.reset();
            if(result.redirect){
               setTimeout(() => {   
                   window.location.href = result.redirect;
               }, 3000);
            }
        }
      } catch (error) {
         console.error(error)
         toastr.error(error.message);
      }
    }
    
});

function handleError(errorResponse) {
    if (errorResponse && errorResponse.errors) {
        if (Array.isArray(errorResponse.errors)) {
            return errorResponse.errors.join(', ');
        }
        if (typeof errorResponse.errors === 'string') {
            return errorResponse.errors;
        }
        if (typeof errorResponse.errors === 'object') {
            const errorMessages = Object.values(errorResponse.errors);
            const errorList = errorMessages.map(item => `<li>${item}</li>`);
            return `<ul>${errorList.join('')}</ul>`;
        }
    }
    return JSON.stringify(errorResponse);
}

    </script>


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


<script>
  /*
* =================// customer login submission starts//============================
*/ 

let loginStep = 1;
document.querySelector('#loginForm').addEventListener('submit',async(event)=> {
  event.preventDefault();
  try {
    if(loginStep == 1){
        toastr.info('Please Wait A While');
        const url = "{{route('user.auth.login-otp')}}";
        const form = event.target;
        const formData = new FormData(form);
         if (!formData) {
            throw new Error('Please fill the form properly');
         }
        const res = await fetch(url, {
            method: 'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            body: formData
        });
        if (!res.ok) {
            const error = await res.json(); 
            throw new Error(error.message); 
        }
        const result = await res.json();
        toastr.success(result.message);
        form.phone.insertAdjacentHTML('afterend', `<input type="text" name="otp" value="${result.otp}" required placeholder="OTP"/>`);
        loginStep++;
    }else
    {
        const url = "{{route('user.auth.login')}}";
        const form = event.target;
        const formData = new FormData(form);
         if (!formData) {
            throw new Error('Please fill the form properly');
         }
        const res = await fetch(url, {
            method: 'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            body: formData
        });
        if (!res.ok) {
            const error = await res.json(); 
            throw new Error(error.message); 
        }
        const result = await res.json();
        if(result.link){
          window.location.href = result.link;
        }


    }
  } catch (error) {
    toastr.error(error.message);

  }
})

</script>

</body>

</html>