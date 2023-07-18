    <link rel="shortcut icon" href="{{ company_fav_icon(@base_settings('company_icon')) }}" type="image/x-icon" >
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('public/vendors/') }}/fontawesome/css/all.min.css">
    <!-- Line Awesome -->
    <link rel="stylesheet" href="{{ asset('public/vendors/') }}/lineawesome/css/line-awesome.min.css">

    @if(isRTL())
     <!-- bootstrap rtl  -->
    <link rel="stylesheet" href="{{ asset('public/css/bootstrap.rtl.min.css') }}">
    @else 
    <!--  Bootstrap 5 -->
    <link rel="stylesheet" href="{{ asset('public/vendors/') }}/bootstrap/css/bootstrap.min.css">
    @endif 

    <!-- Metis Menu -->
    <link rel="stylesheet" href="{{ asset('public/vendors/') }}/metis-menu/css/metis-menu.min.css">
    <!-- Apex Chart -->
    <link rel="stylesheet" href="{{ asset('public/vendors/') }}/apexchart/css/apexcharts.min.css">
    <!-- date ranger -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <!-- Input Tags -->
    <link rel="stylesheet" href="{{ asset('public/vendors/') }}/inputtags/tagsinput.css">
    <!-- Line Icons -->
    <link rel="stylesheet" href="{{ asset('public/vendors/') }}/lineicons/lineicons.css">
    <!-- RTL -->
    
    <!-- scrollbar -->
    <link rel="stylesheet" href="{{ asset('public/css/jquery.mCustomScrollbar.min.css') }}">
    <!-- Sweet alert -->
    <link rel="stylesheet" href="{{ asset('public/vendors/') }}/sweet-alert/css/sweet-alert.min.css">
    <!-- select2 -->
    <link rel="stylesheet" href="{{ asset('public/vendors/') }}/select2/css/select2.min.css">
    <!-- toaster -->
    <link rel="stylesheet" href="{{ asset('public/css/toastr.css') }}">

    <link rel="stylesheet" href="{{ url('public/css/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ url('public/css/style.css') }}">