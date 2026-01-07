<div class="text-center footer-craft">
    <h1 class="fw-bolder text-muted" style="font-size: 55px;">Just Cooked!</h1>
    <p class="">Crafted with <span class="text-danger"><svg class="svg-inline--fa fa-heart" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="heart" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M47.6 300.4L228.3 469.1c7.5 7 17.4 10.9 27.7 10.9s20.2-3.9 27.7-10.9L464.4 300.4c30.4-28.3 47.6-68 47.6-109.5v-5.8c0-69.9-50.5-129.5-119.4-141C347 36.5 300.6 51.4 268 84L256 96 244 84c-32.6-32.6-79-47.5-124.6-39.9C50.5 55.6 0 115.2 0 185.1v5.8c0 41.5 17.2 81.2 47.6 109.5z"></path></svg><!-- <i class="fas fa-heart"></i> Font Awesome fontawesome.com --></span> in Bihar</p>
</div>
<footer class="footer d-lg-block d-none">
    <div class="footer-body p-4">
        <ul class="left-panel list-inline mb-0 p-0">
            <li class="list-inline-item"><a href="../dashboard/extra/privacy-policy.html">Privacy Policy</a>
            </li>
            <li class="list-inline-item"><a href="../dashboard/extra/terms-of-service.html">Terms of Use</a>
            </li>
        </ul>
        <div class="right-panel">
            ©
            <script>
                document.write(new Date().getFullYear())
            </script> FoodYari : Developed
            <span class="">
                <svg class="icon-15" width="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M15.85 2.50065C16.481 2.50065 17.111 2.58965 17.71 2.79065C21.401 3.99065 22.731 8.04065 21.62 11.5806C20.99 13.3896 19.96 15.0406 18.611 16.3896C16.68 18.2596 14.561 19.9196 12.28 21.3496L12.03 21.5006L11.77 21.3396C9.48102 19.9196 7.35002 18.2596 5.40102 16.3796C4.06102 15.0306 3.03002 13.3896 2.39002 11.5806C1.26002 8.04065 2.59002 3.99065 6.32102 2.76965C6.61102 2.66965 6.91002 2.59965 7.21002 2.56065H7.33002C7.61102 2.51965 7.89002 2.50065 8.17002 2.50065H8.28002C8.91002 2.51965 9.52002 2.62965 10.111 2.83065H10.17C10.21 2.84965 10.24 2.87065 10.26 2.88965C10.481 2.96065 10.69 3.04065 10.89 3.15065L11.27 3.32065C11.3618 3.36962 11.4649 3.44445 11.554 3.50912C11.6104 3.55009 11.6612 3.58699 11.7 3.61065C11.7163 3.62028 11.7329 3.62996 11.7496 3.63972C11.8354 3.68977 11.9247 3.74191 12 3.79965C13.111 2.95065 14.46 2.49065 15.85 2.50065ZM18.51 9.70065C18.92 9.68965 19.27 9.36065 19.3 8.93965V8.82065C19.33 7.41965 18.481 6.15065 17.19 5.66065C16.78 5.51965 16.33 5.74065 16.18 6.16065C16.04 6.58065 16.26 7.04065 16.68 7.18965C17.321 7.42965 17.75 8.06065 17.75 8.75965V8.79065C17.731 9.01965 17.8 9.24065 17.94 9.41065C18.08 9.58065 18.29 9.67965 18.51 9.70065Z"
                        fill="currentColor"></path>
                </svg>
            </span> by <a href="https://www.givni.in/">Givni Pvt Ltd</a>.
        </div>
    </div>

</footer>
<footer class="footer d-lg-none d-block">
    <div
        style="background-color: white; padding:10px; text-align: center; display: flex; justify-content: space-around; position: fixed; bottom: 0; width: 100%; z-index: 9;">
        <div onclick="location.href='{{route('vendor.dashboard')}}'">
            <i class="feather-home fs-2"></i>
            <div class="fs-6" style="font-weight: 600;">Home</div>
        </div>
        <div>
            <i class="feather-bar-chart fs-2" onclick="location.href='{{route('vendor.report.tax')}}'"></i>
            <div class="fs-6" style="font-weight: 600;">Business</div>
        </div>
        <div style="position: relative;">
            <div
                style="width: 60px; height: 60px; color:white; background: #ffc107; border-radius: 50%; position: absolute; top: -32px; left: -25px; z-index: 1000;">

                <svg class="icon-15" width="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-3.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"
                        fill="currentColor"></path>
                    <path d="M11 16h2v2h-2zm0-10h2v8h-2z" fill="currentColor"></path>
                </svg>
                <div style="font-weight: 600;">Order</div>
            </div>
        </div>
        <div onclick="location.href='{{route('vendor.restaurant-menu.')}}'">
            <i class="feather-edit fs-2"></i>
            <div class="fs-6" style="font-weight: 600;">Menu</div>
        </div>
        
        <div onclick="location.href='{{route('vendor.wallet.index')}}'">
            <i class="feather-credit-card fs-2"></i>
            <div class="fs-6" style="font-weight: 600;">Payment</div>
        </div>
    </div>
</footer>
