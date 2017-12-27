<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{Auth()->user()->avatar?:icon(Auth()->user()->nickname)}}" class="img-circle"
                     alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{Auth::user()->nickname}}</p>
                <a href="#"><i class="fa fa-circle text-success"></i> 在线</a>
            </div>
        </div>
        <!-- search form -->

        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">

            <li class="treeview active">
                <a href="{{route('home')}}">
                    <i class="fa fa-dashboard"></i> <span>首页</span>
                    <span class="pull-right-container">
            </span>
                </a>
            </li>
            @hasanyrole('experience_manager|admin|superAdmin')
            <li class="treeview active">
                <a href="#">
                    <i class="fa  fa-sort-amount-desc"></i>
                    <span>安吉体验中心</span>
                    <span class="pull-right-container">
            </span>
                </a>
                <ul class="treeview-menu">
                    @can('experience_booking_show')
                        <li @if(request()->segment(1)=='experience_bookings') class="active" @endif>
                            <a href="{{route('experience_bookings.index')}}"><i class="fa fa-reorder"></i>订单列表</a>
                        </li>
                    @endcan

                    <li @if(request()->segment(1)=='rooms') class="active" @endif>
                        <a href="{{route('experience_rooms.index')}}"><i class="fa fa-institution"></i> 房间列表</a>
                    </li>

                    @can('experience_comment_show')
                        <li @if(request()->segment(1)=='experience_comments') class="active" @endif>
                            <a href="{{route('experience_comments.index')}}"><i class="fa fa-comments"></i> 评论列表</a>
                        </li>
                    @endcan
                    @can('experience_settings_show')
                        <li @if(request()->segment(1)=='experience_settings') class="active" @endif>
                            <a href="{{route('experience_settings.index')}}"><i class="fa fa-cogs"></i>通用设置</a>
                        </li>
                    @endcan
                </ul>
            </li>
            @endhasanyrole

            @hasanyrole('user_manager|admin|superAdmin')
            <li class="treeview  active">
                <a href="#">
                    <i class="fa fa-sort-amount-desc"></i>
                    <span>用户管理</span>
                    <span class="pull-right-container">
            </span>
                </a>
                <ul class="treeview-menu">
                    <li @if(request()->segment(1)=='user') class="active" @endif ><a href="{{route('user.index')}}"><i
                                    class="fa fa-users"></i>用户列表</a></li>
                    @hasrole('superAdmin')
                    <li @if(request()->segment(1)=='roles') class="active" @endif><a href="{{route('roles.index')}}"><i
                                    class="fa fa-user-md"></i> 角色列表</a></li>
                    <li @if(request()->segment(1)=='permission') class="active" @endif><a
                                href="{{route('permission.index')}}"><i class="fa fa-hand-paper-o"></i> 权限列表</a></li>
                    @endhasrole
                </ul>
            </li>
            @endhasanyrole

            @hasanyrole('art_manager|admin|superAdmin')
            <li class="treeview  active">
                <a href="#">
                    <i class="fa fa-sort-amount-desc"></i>
                    <span>图片展</span>
                    <span class="pull-right-container">
            </span>
                </a>
                <ul class="treeview-menu">
                    <li @if(request()->segment(1)=='art') class="active" @endif ><a href="{{route('art.index')}}"><i
                                    class="fa fa-file-image-o"></i>展示列表</a></li>
                    <li @if(request()->segment(1)=='art_comment') class="active" @endif ><a
                                href="{{route('art_comment.index')}}"><i class="fa fa-comments-o"></i>评论列表</a></li>
                    <li @if(request()->segment(1)=='art_suggestion') class="active" @endif ><a
                                href="{{route('art_suggestion.index')}}"><i class="fa fa-comments-o"></i>用户建议</a></li>

                </ul>
            </li>
            @endhasanyrole

            @hasanyrole('tearoom_manager|admin|superAdmin')
            <li class="treeview  active">
                <a href="#">
                    <i class="fa fa-sort-amount-desc"></i>
                    <span>茶舍</span>
                    <span class="pull-right-container">
            </span>
                </a>
                <ul class="treeview-menu">
                    <li @if(request()->segment(1)=='tearoom_booking') class="active" @endif ><a
                                href="{{route('tearoom_booking.index')}}"><i
                                    class="fa fa-reorder"></i>订单列表</a></li>
                    <li @if(request()->segment(1)=='tearoom') class="active" @endif ><a
                                href="{{route('tearoom.index')}}"><i class="fa fa-institution"></i>茶舍列表</a></li>
                </ul>
            </li>
            @endhasanyrole

        </ul>
    </section>
    <!-- /.sidebar -->
</aside>