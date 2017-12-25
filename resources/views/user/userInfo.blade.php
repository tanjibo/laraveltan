@extends('layouts.main')

@section('content')
    <div class="content" id="app">
        <div class="row">

            {{--<div class="col-lg-3 col-md-3 hidden-sm hidden-xs user-info">--}}
                {{--<div class="panel panel-default">--}}
                    {{--<div class="panel-body">--}}
                        {{--<div class="media">--}}
                            {{--<div align="center">--}}
                                {{--<img class="thumbnail img-responsive" src="{{auth()->user()->avatar}}" width="150px" height="150px">--}}
                            {{--</div>--}}
                            {{--<div class="media-body">--}}
                                {{--<hr>--}}
                                {{--<h4><strong>个人简介</strong></h4>--}}
                                {{--<p>暂无</p>--}}
                                {{--<hr>--}}
                                {{--<h4><strong>注册于</strong></h4>--}}
                                {{--<p>{{auth()->user()->created_at}}</p>--}}
{{--                                <p><a href="{{route('user.edit',auth()->id())}}">编辑个人资料</a></p>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                {{-- 用户发布的内容 --}}
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="col-md-12">
                            <el-form ref="form" :model="form" :rules="rules" label-width="100px">
                                <div class="box box-solid">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">个人资料</h3>
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body">

                                        <div class="box-group" id="accordion">

                                            <div class="panel box box-primary">
                                                <div class="box-header with-border">
                                                    <h4 class="box-title" data-toggle="collapse" data-parent="#accordion"
                                                        href="#collapseOne">

                                                        <el-button type="primary" plain>基本信息</el-button>

                                                    </h4>
                                                </div>
                                                <div id="collapseOne" class="panel-collapse collapse in">
                                                    <div class="box-body">

                                                        <el-form-item label="昵称" prop="nickname">
                                                            <el-input v-model="form.nickname" placeholder="昵称"></el-input>
                                                        </el-form-item>
                                                        <el-form-item label="真实名称" prop="username">
                                                            <el-input v-model="form.username" placeholder="真实名称"></el-input>
                                                        </el-form-item>


                                                        <el-form-item label="用户头像" prop="avatar">
                                                            <uploader-single :imgurl.sync="form.avatar">
                                                            </uploader-single>
                                                        </el-form-item>


                                                        <el-form-item label="性别" prop="gender">
                                                            <el-radio-group v-model="form.gender">
                                                                <el-radio label="0">未知</el-radio>
                                                                <el-radio label="1">男</el-radio>
                                                                <el-radio label="2">女</el-radio>
                                                            </el-radio-group>
                                                        </el-form-item>
                                                        <el-form-item label="邮箱">
                                                            <el-input v-model="form.email" placeholder="邮箱"></el-input>
                                                        </el-form-item>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="panel box box-success">
                                                <div class="box-header with-border">
                                                    <h4 class="box-title">
                                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                                                            <el-button type="success" plain>修改密码</el-button>
                                                        </a>
                                                    </h4>
                                                </div>
                                                <div id="collapseThree" class="panel-collapse collapse in">
                                                    <div class="box-body">

                                                        <el-form-item label="密码">
                                                            <el-input type="password" v-model="form.password"
                                                                      auto-complete="off"></el-input>
                                                        </el-form-item>
                                                        <el-form-item  label="确认密码">
                                                            <el-input type="password" v-model="checkPassword"
                                                                      auto-complete="off"></el-input>
                                                        </el-form-item>


                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                    <!-- /.box-body -->
                                    <div class="box-footer">
                                        <el-form-item>
                                            <el-button type="primary" @click="submitForm('form')">保存数据</el-button>
                                        </el-form-item>
                                    </div>
                                </div>
                            </el-form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@stop

@section('javascript')
    <script>
        let model = {!! auth()->user()->toJson()!!};
        console.log(model)

        let vm = new Vue({
            el: '#app',
            data: {
                checkPassword:'',
                form: model,
                rules: {
                    nickname: [
                        { required: true, message: '昵称不能为空', trigger: 'blur' },
                        { min: 2, max: 20, message: '长度在 3 到 20 个字符', trigger: 'blur' }
                    ],
                    username: [
                        { required: true, message: '用户名不能为空', trigger: 'change' }
                    ],

                    avatar: [
                        {  required: true, message: '请上传头像', trigger: 'change' }
                    ],

                }
            },

            methods: {
                submitForm(formName) {

                    if(this.checkPassword || this.form.password){
                        if(this.checkPassword!==this.form.password){
                            this.$message.error('两次密码不一致');
                            return;
                        }
                    }
                    this.$refs[formName].validate((valid) => {
                        if (valid) {
                            this.form._method='PATCH';
                            this.$http.post('{{route('user.update',auth()->user())}}',this.form).then(res=>{
                                reload();
                            })
                        } else {
                            this.$message.error('请完善信息');
                            return false;
                        }
                    });
                },
                resetForm(formName) {
                    this.$refs[formName].resetFields();
                }
            }
        })
    </script>
@stop