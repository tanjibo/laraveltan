@extends('layouts.main')

@section('content')
    <div class="content" id="app">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">添加角色</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal" @submit.prevent="submit">
                <div class="box-body">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">角色名称</label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" v-model="name" placeholder="角色名称">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">所属guard</label>
                        <div class="col-sm-10">
                            <input type="text" v-model="guard_name" class="form-control" placeholder="默认值:web">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">权限</label>
                        <div class="col-sm-10">
                            <el-select v-model="permission" multiple placeholder="请选择">
                                <el-option
                                        v-for="item in options"
                                        :key="item.name"
                                        :label="item.name"
                                        :value="item.name">
                                </el-option>
                            </el-select>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" class="btn btn-success pull-right">更新</button>

                </div>
                <!-- /.box-footer -->
            </form>
        </div>
    </div>
@stop
@section('javascript')
    <script>
        let data={!! $permission->toJson() !!};
        let model={!! $model->toJson() !!}
        let select={!! $model->permissions()->pluck('name')->toJson() !!}
        new Vue({
            el: '#app',
            data: {
                name: model.name,
                guard_name: model.guard_name,
                options:data,
                loading: false,
                permission:select


            },
            methods: {
                submit(){
                    if (!(this.name && this.guard_name &&this.permission.length)) {
                        this.$message.error('请输入完整信息');
                        return
                    }
                    this.$http.post('{{route('roles.update',$model)}}', {
                        name: this.name,
                        guard_name: this.guard_name,
                        permission:this.permission,
                        _method:'PUT'
                    }).then(res => {
                        this.$message.success("更新成功");
                        setTimeout(()=>{
                           window.location.href="{{route('roles.index')}}"
                        },1500)
                    }).catch(res => {
                        this.$alert(res.message, '警告', {
                            confirmButtonText: '确定',
                        });
                    })
                },
            }
        })
    </script>
@stop