
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>了如三舍 |登录 </title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="adminLTEcomponents/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="adminLTEcomponents/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="adminLTEcomponents/Ionicons/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="adminLTEcomponents/dist/css/AdminLTE.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="adminLTEcomponents/plugins/iCheck/square/blue.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->


    <style>
        body,.login-page, .register-page{
            background:#e7edee;
        }
    </style>
</head>
<body class="hold-transition register-page">
<div class="register-box">
    <div class="register-logo">
        <a href="../../index2.html"><b>LRSS</b>ADMIN</a>
    </div>
    @include('flash::message')
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="register-box-body" style="box-shadow: 8px 8px 0px rgba(255,255,255,0.1),-8px 8px 0px rgba(255,255,255,0.1),8px -8px 0px rgba(255,255,255,0.1),-8px -8px 0px rgba(255,255,255,0.1)">
        <p class="login-box-msg">欢迎使用了如三舍管理后台</p>

        <form action="{{route("login")}}" method="post">
            <div class="form-group has-feedback">
                {{csrf_field()}}
                <input type="text" class="form-control" placeholder="您的用户名" name="mobile" value="{{old('mobile')}}">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>

            <div class="form-group has-feedback">
                <input type="password" class="form-control" placeholder="密码" name="password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>

            <div class="row">
                <!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">登录</button>
                </div>
                <!-- /.col -->
            </div>
        </form>
    </div>
    <!-- /.form-box -->
</div>
<!-- /.register-box -->

<!-- jQuery 3 -->
<script src="adminLTEcomponents/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="adminLTEcomponents/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script>
    $('#flash-overlay-modal').modal();
</script>
</body>
</html>
