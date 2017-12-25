@extends('layouts.main')

@section('content')
    <div class="content" id="app">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">添加权限</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal" @submit.prevent="submit">
                <div class="box-body">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">权限名称</label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" v-model="name" placeholder="权限名称">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">所属guard</label>

                        <div class="col-sm-10">
                            <input type="text" v-model="guard_name" class="form-control" placeholder="默认值:web">
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" class="btn btn-success pull-right">添加</button>
                    <button type="reset" class="btn btn-info pull-right">重置</button>
                </div>
                <!-- /.box-footer -->
            </form>
        </div>
    </div>
@stop
@section('javascript')
    <script>
        new Vue({
            el: '#app',
            data: {
                name: '',
                guard_name: 'web',
            },
            methods: {
                submit(){
                    if (!(this.name && this.guard_name)) {
                        this.$message.error('请输入完整信息');
                        return
                    }
                    this.$http.post('{{route('permission.store')}}', {
                        name: this.name,
                        guard_name: this.guard_name
                    }).then(res => {
                        this.$message.success("添加成功");
                    }).catch(res => {
                        this.$alert(res.message, '警告', {
                            confirmButtonText: '确定',
                        });
                    })
                }
            }
        })
    </script>
@stop