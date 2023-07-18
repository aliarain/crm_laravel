<footer>
    <div class="container">
        <div class="footer-middle">
            <div class="row gy-5">
                <div class="col-md-12 col-lg-12">
                    <div class="footer-logo">
                        <img class="full-logo  light_logo " src="{{ asset('public/assets/images/white.png') }}" alt="white" >
                        <p class="my-3">Our software agency is leveraging the latest technological advancements and imparting our knowledge to assist you in achieving your goals. We are committed to guiding you every step of the way to ensure success.</p>
                        <p class="privacy_policy_text">
                            <a href="{{url('privacy-policy')}}">Privacy Policy</a> |
                            <a href="{{url('terms-and-conditions')}}">Terms & Conditions</a>
                        </p>
                    </div>
                </div>

            </div>
        </div>

    </div>
    <div class="footer-bottom">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="copy-right d-flex justify-content-center">
                        <p> &copy; {{date('Y')}}. {{ _trans('installer.All right reserved') }}</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<script src="{{ asset('public/backend/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 5 -->
<script src="{{ asset('public/frontend/assets/bootstrap/bootstrap.min.js') }}"></script>
<script src="{{ asset('/') }}public/frontend/assets/slick.min.js"></script>
<script src="{{ asset('/') }}public/frontend/js/__header.js"></script>
<script src="{{ asset('/') }}public/frontend/js/__accordion.js"></script>
<script src="{{ asset('/') }}public/frontend/js/__scrollUp.js"></script>
<script src="{{ asset('/') }}public/frontend/js/__sideMenuLang.js"></script>
<script src="{{ asset('/') }}public/frontend/js/__mobileViewNavMenu.js"></script>
<script src="{{ asset('/') }}public/frontend/js/slider.js"></script>
</body>

</html>