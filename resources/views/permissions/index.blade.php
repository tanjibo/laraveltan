@extends('layouts.main')

@section('content')
    <div class="content">
        <div class="box">
            <div class="box-header">
                <span class="box-title">
                    权限列表
                </span>
                <a href="{{route('permission.create')}}" class="btn btn-success btn-sm pull-right">添加</a>
                <button class="btn btn-danger btn-sm pull-right"
                        onclick="delMass('{{route('permission.mass_destroy')}}')">删除选中
                </button>
            </div>

            <div class="box-body">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th><input type="checkbox" name="name" value=""></th>
                        <th>ID</th>
                        <th>名称</th>
                        <th>所属组</th>
                        <th>创建时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($model as $v)
                        <tr>
                            <td><input type="checkbox" name="name" value="{{$v->id}}"></td>
                            <td>{{$v->id}}</td>
                            <td>{{$v->name}}</td>
                            <td>{{$v->guard_name}}</td>
                            <td>{{$v->created_at->diffForHumans()}}</td>
                            <td>
                                <a class="btn btn-sm btn-primary" href="{{route('permission.edit',$v)}}">编辑</a>
                                <a class="btn btn-sm btn-danger"
                                   onclick="del('{{route("permission.destroy",$v)}}')">删除</a>
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop
@section('javascript')
    <script>

        $(function () {
            $('thead [type=checkbox]').click(function () {
                $(this).prop('checked') ? $('[type=checkbox]').prop('checked', true) : $('[type=checkbox]').prop('checked', false)
            })
        })

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

        function delMass(url) {
            let inputArr = $('tbody input:checked');
            let arr = [];

            if (inputArr.length <= 0) return false;

            inputArr.each(function () {
                arr.push($(this).val());
            })


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
                    axios.post(url, {ids: arr}).then(res => {
                      window.location.reload();
                    })
                }
            }).catch(res => {

            })
        }
    </script>
@stop
