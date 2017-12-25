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
                        <label class="col-sm-2 control-label">用户名称</label>

                        <div class="col-sm-10">
                            <input type="text" disabled value="{{$user->nickname}}" class="form-control" placeholder="角色名称">
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-2 control-label">权限</label>
                        <div class="col-sm-10">
                            <el-select v-model="options" multiple placeholder="请选择">
                                <el-option
                                        v-for="item in roles"
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
        let roles={!! $roles->toJson() !!};
        let options={!! $hasRole->toJson() !!}


   let   vm=  new Vue({
            el: '#app',
            data: {
                roles:roles,
                options:options,

            },
            methods: {
                submit(){
                    if (!this.options.length) {
                        this.$message.error('请输入完整信息');
                        return
                    }
                    this.$http.post('', {

                        roles:this.options

                    }).then(res => {
                           reload();

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