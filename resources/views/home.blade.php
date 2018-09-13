@extends("layouts.main")

@section("content")

    <!-- Content Header (Page header) -->


    <!-- Main content -->
    <section class="content">
        <div class="callout callout-info">
            <h4>温馨提示!</h4>

            <p>本后台主题采用最新前端技术实现,抛弃传统旧浏览器的兼容问题.为了确保您的体验，我们强烈建议您使用google浏览器.</p>
        </div>
        <!-- Default box -->

        <!-- /.box -->
    </section>
    <section class="conttent">
        <div id="main" style="width: 600px;height:400px;"></div>
    </section>
    <!-- /.content -->
@endsection

@section('javascript')
    <script src="https://cdn.bootcss.com/echarts/4.1.0.rc2/echarts.min.js"></script>
    <script>
        var myChart = echarts.init(document.getElementById('main'));

        // 指定图表的配置项和数据
        var option = {
            title: {
                text: 'ECharts 入门示例'
            },
            tooltip: {},
            legend: {
                data:['销量']
            },
            xAxis: {
                data: ["衬衫","羊毛衫","雪纺衫","裤子","高跟鞋","袜子"]
            },
            yAxis: {},
            series: [{
                name: '销量',
                type: 'bar',
                data: [5, 20, 36, 10, 10, 20]
            }]
        };

        // 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option);
    </script>
@stop