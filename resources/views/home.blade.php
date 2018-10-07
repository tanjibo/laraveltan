@extends("layouts.main")

@section("content")

    <!-- Content Header (Page header) -->


    <!-- Main content -->
    <section class="content">
        <div class="callout callout-info">
            <h4>温馨提示!</h4>

            <p>本后台主题采用最新前端技术实现,抛弃传统旧浏览器的兼容问题.为了确保您的体验，我们强烈建议您使用google浏览器.</p>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-aqua"><i class="fa fa-user-circle-o"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">用户总数</span>
                            <span class="info-box-number">{{$totalUserNum}}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-red"><i class="fa  fa-rmb"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">安吉线上总流水</span>
                            <span class="info-box-number" style="color:red">￥{{$barChart->totalBillNum}}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->

                <!-- fix for small devices only -->
                <div class="clearfix visible-sm-block"></div>

                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-green"><i class="fa fa-cart-plus"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">安吉完成订单</span>
                            <span class="info-box-number">{{$barChart->totalCompleteOrderCount}}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <!-- /.col -->
            </div>
            <div class="row" style="margin-top: 20px;">

                <div class="col-md-12" style="margin-top:20px;">
                    {!! $barChart->container() !!}
                </div>
                <div class="col-md-12" style="margin-top:20px;">
                    {!! $lineChart->container() !!}
                </div>
                <div class="col-md-12" style="margin-top:20px;">
                    {!! $userLineChart->container() !!}
                </div>

            </div>
        </div>
        <!-- Default box -->

        <!-- /.box -->
    </section>

    <!-- /.content -->
@endsection

@section('javascript')
    <script src="{{asset("js/echart.4.1.0.js")}}"></script>
    {!! $barChart->script() !!}
    {!! $lineChart->script() !!}
    {!! $userLineChart->script() !!}

@stop