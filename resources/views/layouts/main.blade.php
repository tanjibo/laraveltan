<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>了如三舍后台管理系统|首页</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="/css/all.css">
    <link href="https://cdn.bootcss.com/element-ui/2.0.5/theme-chalk/index.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>


    <![endif]-->
 @yield('css')
    <!-- Google Font -->
</head>
<!-- ADD THE CLASS sidebar-collapse TO HIDE THE SIDEBAR PRIOR TO LOADING THE SITE -->
<body class="hold-transition skin-blue sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">

@includeIf("layouts._header")

<!-- =============================================== -->


@includeIf("layouts._aside")
<!-- =============================================== -->

    <div class="content-wrapper">
    @include('flash::message')

    <!-- Content Wrapper. Contains page content -->
    @include("layouts._breadcrumb")
    @yield("content")
    <!-- /.content-wrapper -->
    </div>

    @includeIf("layouts._footer")
</div>
<!-- ./wrapper -->
<script src="/js/all.js"></script>
<!-- SlimScroll -->
{{--<script src="/adminLTEcomponents/jquery-slimscroll/jquery.slimscroll.min.js"></script>--}}
<!-- FastClick -->
{{--<script src="/adminLTEcomponents/fastclick/lib/fastclick.js"></script>--}}
<!-- AdminLTE App -->
<!-- AdminLTE for demo purposes -->
<script src="{{mix('js/manifest.js')}}"></script>
<script src="{{mix('js/vendor.js')}}"></script>
<script src="{{mix('js/app.js')}}"></script>
<script src="/js/laroute.js"></script>
{{--<script src="/adminLTEcomponents/dist/js/demo.js"></script>--}}
{{--<script src="https://cdn.bootcss.com/element-ui/2.0.5/index.js"></script>--}}

<script>
    $('#flash-overlay-modal').modal();
//    $('div.alert').not('.alert-important').delay(1000).fadeOut(350);
</script>
@yield('javascript')
</body>
</html>
