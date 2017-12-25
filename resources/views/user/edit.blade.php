@extends('layouts.main')

@section('content')
    <div class="content" id="app">
        <div class="row">
            <div class="col-md-12">
                <el-form ref="form" :model="form" :rules="rules" label-width="100px">
                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">编辑用户资料</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">

                            <div class="box-group" id="accordion">
                                <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
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
                                            <el-form-item label="手机号码" prop="mobile">
                                                <el-input v-model="form.mobile" placeholder="手机号"></el-input>
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
                                            <el-form-item label="累计积分">
                                                <el-input placeholder="累计积分" v-model="form.total_credit"></el-input>
                                            </el-form-item>
                                            <el-form-item  label="剩余积分">
                                                <el-input v-model="form.surplus_credit" placeholder="剩余积分"></el-input>
                                            </el-form-item>
                                            <el-form-item  label="余额">
                                                <el-input v-model="form.balance" placeholder="余额"></el-input>
                                            </el-form-item>
                                            <el-form-item label="设备">
                                                <el-select v-model="form.device" placeholder="请选择">
                                                    <el-option label="微信" value="0"></el-option>
                                                    <el-option label="IOS" value="1"></el-option>
                                                    <el-option label="安卓" value="2"></el-option>
                                                    <el-option label="网页" value="3"></el-option>
                                                    <el-option label="其他" value="9"></el-option>
                                                </el-select>
                                            </el-form-item>
                                            <el-form-item label="来源">
                                                <el-radio-group v-model="form.source">
                                                    <el-radio label="0">商城</el-radio>
                                                    <el-radio label="1">安吉</el-radio>
                                                    <el-radio label="2">茶社</el-radio>
                                                    <el-radio label="11">旅游族</el-radio>
                                                </el-radio-group>
                                            </el-form-item>
                                            <el-form-item label="状态">
                                                <el-radio-group v-model="form.status">
                                                    <el-radio label="1">正常</el-radio>
                                                    <el-radio label="0">锁定</el-radio>
                                                </el-radio-group>
                                            </el-form-item>


                                        </div>
                                    </div>
                                </div>


                                <div class="panel box box-danger">
                                    <div class="box-header with-border">
                                        <h4 class="box-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                                                <el-button type="success" plain>其他信息</el-button>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseTwo" class="panel-collapse collapse">
                                        <div class="box-body">
                                            <el-form-item label="年龄">
                                                <el-input v-model="form.age" placeholder="请输入用户年龄"></el-input>
                                            </el-form-item>
                                            <el-form-item label="民族">
                                                <el-input v-model="form.nationality" placeholder="民族"></el-input>
                                            </el-form-item>
                                            <el-form-item label="专业">
                                                <el-input v-model="form.profession" placeholder="专业"></el-input>
                                            </el-form-item>
                                            <el-form-item label="学历">
                                                <el-select v-model="form.education" placeholder="请选择">
                                                    <el-option label="大专" value="大专"></el-option>
                                                    <el-option label="本科" value="本科"></el-option>
                                                    <el-option label="硕士" value="硕士"></el-option>
                                                    <el-option label="博士" value="博士"></el-option>
                                                    <el-option label="其他" value="其他"></el-option>
                                                </el-select>
                                            </el-form-item>
                                            <el-form-item label="身份证">
                                                <el-input v-model="form.id_card"></el-input>
                                            </el-form-item>
                                            <el-form-item label="出生日期">
                                                <el-date-picker type="date" placeholder="选择日期" v-model="form.brithday"
                                                                style="width: 100%;"></el-date-picker>
                                            </el-form-item>
                                            <el-form-item label="微信号">
                                                <el-input v-model="form.wechat"></el-input>
                                            </el-form-item>
                                            <el-form-item label="qq号">
                                                <el-input v-model="form.qq"></el-input>
                                            </el-form-item>
                                            <el-form-item label="微博">
                                                <el-input v-model="form.weibo"></el-input>
                                            </el-form-item>
                                            <el-form-item label="关注来源">
                                                <el-checkbox-group v-model="form.intention">
                                                    <el-checkbox label="社区"></el-checkbox>
                                                    <el-checkbox label="微信"></el-checkbox>
                                                    <el-checkbox label="微博"></el-checkbox>
                                                    <el-checkbox label="其他"></el-checkbox>
                                                </el-checkbox-group>
                                            </el-form-item>
                                            <el-form-item label="备注(不超过150个字)">
                                                <el-input type="textarea" v-model="form.remark"></el-input>
                                            </el-form-item>


                                        </div>
                                    </div>
                                </div>
                                <div class="panel box box-success">
                                    <div class="box-header with-border">
                                        <h4 class="box-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                                                <el-button type="success" plain>内部员工信息</el-button>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseThree" class="panel-collapse collapse in">
                                        <div class="box-body">
                                            <el-form-item label="内部员工">
                                                <el-switch v-model="form.is_lrss_staff"></el-switch>
                                            </el-form-item>
                                            <el-form-item  v-if="form.is_lrss_staff" label="密码">
                                                <el-input type="password" v-model="form.password"
                                                          auto-complete="off"></el-input>
                                            </el-form-item>
                                            <el-form-item  v-if="form.is_lrss_staff"  label="确认密码">
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
                                <el-button type="primary" @click="submitForm('form')">立即创建</el-button>
                                <el-button @click="resetForm('form')">重置</el-button>
                            </el-form-item>
                        </div>
                    </div>
                </el-form>
            </div>
        </div>
    </div>

@stop

@section('javascript')
    <script>
        let model = {!! $model !!};
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
                    mobile: [
                        {  required: true, message: '请输入手机号', trigger: 'change' }
                    ],

//                    avatar: [
//                        {  required: true, message: '请上传头像', trigger: 'change' }
//                    ],

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
                            this.$http.post('{{route('user.update',$model)}}',this.form).then(res=>{
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
