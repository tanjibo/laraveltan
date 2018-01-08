<header class="main-header">
    <!-- Logo -->
    <a class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>LR</b>SS</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>Admin</b>LRSS</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                @if(auth()->user()->notification_count>0)
                <li class="notifications-menu">
                    <a href="{{route('notification.index')}}">
                       <i class="fa fa-bell-o"></i>
                           <span class="label label-danger">{{auth()->user()->notification_count}}</span>
                    </a>
                </li>
                @endif

                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="{{Auth::user()->avatar?:icon(Auth::user()->nickname)}}" class="user-image" alt="User Image">
                        <span class="hidden-xs">{{Auth::user()->nickname}}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="{{Auth::user()->avatar?:icon(Auth::user()->nickname)}}" class="img-circle" alt="User Image">

                            <p>
                                {{Auth::user()->nickname}}
                                <small>注册于 {{Auth::user()->created_at->diffForHumans()}}</small>
                                <small>正在使用 {{Auth::user()->terminal}} 登录</small>
                            </p>
                        </li>
                        <!-- Menu Body -->
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{route('user.show',auth()->id())}}" class="btn btn-default btn-flat">个人信息</a>
                            </div>
                            <div class="pull-right">
                                <a href="{{route("logout")}}" class="btn btn-default btn-flat">退出登录</a>
                            </div>
                        </li>
                    </ul>
                </li>
                <!-- Control Sidebar Toggle Button -->
                <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>
            </ul>
        </div>
    </nav>
</header>