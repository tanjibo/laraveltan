@extends('layouts.main')

@section('content')
    <div class="content" id="app">
        <el-form ref="form"  label-width="80px">
            <div class="box">
                <div class="box-header">
                <span class="box-title">
                    活动列表
                </span>
                    <a href="{{route('official_activity.create')}}" class="btn btn-success btn-sm pull-right">添加活动</a>
                    <a style="margin-right:3px;" onclick="reset('{{route("official_account_setting.reset")}}')" class="btn btn-danger btn-sm pull-right">重置微信公众号菜单栏</a>

                    <a style="margin-right:3px;" href="{{route('official_account_setting.index')}}" class="btn btn-info btn-sm pull-right">修改公众号配置</a>

                </div>
                <div class="box-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>名称</th>
                            <th>开始时间</th>
                            <th>结束时间</th>
                            <th>活动是否结束</th>
                            <th>创建时间</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($model as $v)
                            <tr>
                                <td>{{$v->id}}</td>
                                <td>{{$v->name}}</td>
                                <td>{{\Carbon\Carbon::parse($v->start_time)->toDateString()}}</td>
                                <td>
                                    {{\Carbon\Carbon::parse($v->end_time)->toDateString()}}
                                </td>
                                <td>

                                    @if($v->is_active==0)
                                        <el-tag type="info" size="mini">未进行</el-tag>
                                    @elseif($v->is_active==1)
                                        <el-tag type="success" size="mini">进行中</el-tag>
                                    @else
                                        <el-tag type="danger" size="mini">已结束</el-tag>
                                    @endif
                                </td>
                                <td>{{$v->created_at->diffForHumans()}}</td>
                                <td>
                                    <a class="btn btn-sm btn-primary" href="{{route('official_activity.edit',$v)}}">编辑</a>
                                    @if($v->is_active==0)
                                        @if($v->share_settings->first())
                                        <a class="btn btn-sm btn-primary" onclick="launch('{{route("official_activity.launch",['official_activity_id'=>$v])}}')">启动</a>
                                            @endif
                                    @elseif($v->is_active==1)
                                        <a class="btn btn-sm btn-danger" onclick="launch('{{route("official_activity.launch",['official_activity_id'=>$v,'close'=>'close'])}}','close')">关闭</a>
                                    @else
                                        <el-tag type="danger" size="mini">已结束</el-tag>
                                    @endif

                                    <a class="btn btn-sm btn-danger"
                                       onclick="del('{{route("official_activity.destroy",$v)}}')">删除</a>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </el-form>
    </div>
@stop
@section('javascript')

    <script>

       function launch(url,close){
            swal({
                title: '确定启动吗?',
                html: "这将产生一系列以下操作:<br/> " +
                "* 将重新生成公众号菜单栏 <br/>" +
                (!close?"* 其他活动都将关闭<br/> ":"") +
                "* 所以公众号互动用语重置",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '确认!'
            }).then((result) => {
                if (result) {

                    axios.post(url).then(res => {
                        window.location.reload();
                    })
                }
            }).catch(res => {

            })
        }

       function reset(url){
           swal({
               title: '确定重置吗?',
               html: "这将产生一系列以下操作:<br/> " +
               "* 将重新生成公众号菜单栏 <br/>" +
               "* 所以公众号互动用语重置",
               type: 'warning',
               showCancelButton: true,
               confirmButtonColor: '#3085d6',
               cancelButtonColor: '#d33',
               confirmButtonText: '确认!'
           }).then((result) => {
               if (result) {

                   axios.post(url).then(res => {
                       window.location.reload();
                   })
               }
           }).catch(res => {

           })
       }

        function del(url) {
            swal({
                title: '确定删除吗?',
                text: "这将导致数据不能回复!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '删除!'
            }).then((result) => {
                if (result) {
                    axios.post(url, {_method: "DELETE"}).then(res => {
                        window.location.reload();
                    })
                }
            }).catch(res => {

            })
        }




            vm = new Vue({
                el: '#app',
                data: {

                },

                methods: {

                }

            })

    </script>
@endsection
