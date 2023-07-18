    <!-- jQuery -->
    <script src="{{ asset('public/vendors/') }}/jquery/jquery-3.6.0.min.js"></script>
    <!--  Bootstrap 5 -->
    <script src="{{ asset('public/vendors/') }}/bootstrap/js/popper.min.js"></script>
    <script src="{{ asset('public/vendors/') }}/bootstrap/js/bootstrap.min.js"></script>
    <!-- Metis Menu -->
    <script src="{{ asset('public/vendors/') }}/metis-menu/js/metis-menu.min.js"></script>
    <!-- date ranger -->
    <script src="{{ asset('public/vendors/') }}/daterangepicker/js/moment.min.js"></script>
    <script src="{{ asset('public/vendors/') }}/daterangepicker/js/daterangepicker.min.js"></script>
    <!-- sweet alert -->
    <script src="{{ asset('public/vendors/') }}/sweet-alert/js/sweetalert2@11.min.js"></script>
    <script src="{{ asset('public/vendors/') }}/select2/js/select2.min.js"></script>
    {{-- Js --}}
    <script src="{{ asset('public/backend/') }}/js/jquery-ui.js"></script>
    {{-- toastr --}}
    <script src="{{ asset('public/js/toastr.js') }}"></script>
    {!! Toastr::message() !!}
    @if (Session::has('toastr'))
    @endif
    {{-- toastr --}}
    <script src="{{ asset('public/js/') }}/tooltip.js"></script>
    <script src="{{ asset('public/js/') }}/theme.js" async></script>
    <!-- Input Tags -->
    <script src="{{ asset('public/vendors/') }}/inputtags/tagsinput.js"></script>
    <script src="{{ asset('public/backend/js/main.js') }}"></script>
  

    {{-- scrollbar plugin  --}}
    <script src="{{ asset('public/js/jquery.mCustomScrollbar.min.js') }}"></script>

    @stack('scripts')
    {{-- axios load --}}
    <script src="{{ asset('public/js/axios.js') }}"></script>
    {{-- axios load --}}
    <script>
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
    </script>
    <script src="{{ asset('public/backend/js/fs_d_ecma/configuration/configuration.js') }}"></script>
    <script>
        // sidebar_scrollbar_active 
        $(".sidebar_scrollbar_active").mCustomScrollbar({
            setTop: 0,
            autoHideScrollbar : true,
            mouseWheel : true,
        });
        if ($(".card_scroll_active .card-body").length > 0) {
            $(".card_scroll_active .card-body").mCustomScrollbar({
                setTop: 0,
                autoHideScrollbar: true,
                mouseWheel: true,
            });
        }
        function responsiveScroll(){
            $(".table_scrollbar_active").mCustomScrollbar({
                axis:"x" ,
                setHeight: false,
                setWidth: false
            });
        }

        responsiveScroll()

    </script>