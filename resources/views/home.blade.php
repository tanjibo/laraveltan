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
                <div class="col-md-12">
                    {!! $barChart->container() !!}
                </div>
                <div class="col-md-12">
                    {!! $lineChart->container() !!}
                </div>
                <div class="col-md-12">
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
    <script src="https://cdn.bootcss.com/echarts/4.1.0.rc2/echarts.min.js"></script>
    {!! $barChart->script() !!}
    {!! $lineChart->script() !!}
    {!! $userLineChart->script() !!}

@stop