@extends('layouts.main')

@section('content')
    <div class="content" id="app">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">小程序通用设置</h3>
            </div>
            <!-- /.box-header -->
            <el-form :model="mini" :rules="mini_rules" ref="ruleForm" label-width="150px" >
                <div class="box-body">
                    <div>
                        <el-form-item label="选择小程序">
                            <el-select v-model="mini.mini_type" placeholder="请选择活动区域" prop="mini_type">
                                <el-option label="安吉" value="{{\App\Models\MiniCommonSettings::MINI_TYPE_EXPERIENCE}}"></el-option>
                                <el-option label="茶舍" value="{{\App\Models\MiniCommonSettings::MINI_TYPE_TEAROOM}}"></el-option>
                            </el-select>
                        </el-form-item>
                        <el-form-item label="banner图片" prop='banner_url'>
                            <uploader-single :imgurl.sync="mini.banner_url"></uploader-single>
                        </el-form-item>

                        <el-form-item  label="小程序整体色调" prop="common_color">
                            <el-color-picker  v-model="mini.common_color"></el-color-picker>
                            <el-input  v-model="mini.common_color"></el-input>
                        </el-form-item>

                        <el-form-item label="导航条颜色" prop="navigation_bar_color">
                            <el-color-picker v-model="mini.navigation_bar_color"></el-color-picker>
                            <el-input v-model="mini.navigation_bar_color"></el-input>
                        </el-form-item>
                    </div>
                </div>
            </el-form>

            <div class="box-footer">
                <el-button type="success" @click.prevent="sbuMiniSetting">保存</el-button>
            </div>
            <!-- /.box-body -->
        </div>
        <div class="box room_common box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">房间通用配套设备</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

                <uploader :imgurl.sync="img"></uploader>

            </div>
            <div class="box-footer">
                <el-button type="success" @click.prevent="subImg">保存</el-button>
            </div>
            <!-- /.box-body -->
        </div>
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">常见问题简介</h3>
            </div>
            <!-- /.box-header -->
            <el-form>
                <div class="box-body">
                    <editor :id="'id1'" :editdata.sync="question"></editor>
                </div>
                <div class="box-footer">
                    <el-form-item>
                        <el-button type="success" @click.pevent="subQuestion">保存</el-button>
                    </el-form-item>
                </div>
            </el-form>
            <!-- /.box-body -->
        </div>
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">退订政策</h3>
            </div>
            <!-- /.box-header -->
            <el-form>
                <div class="box-body">
                    <editor :id="'id2'" :editdata.sync="tip"></editor>

                </div>
                <div class="box-footer">
                    <el-button type="success" @click="subTip">保存</el-button>
                </div>
            </el-form>

            <!-- /.box-body -->
        </div>
    </div>
@stop
@section('css')
    <style>
        .room_common   .el-upload-list--picture-card .el-upload-list__item {
            width: 90px !important;
            height: 105px !important;

        }

        .room_common   .el-upload--picture-card {
            width: 90px !important;
            height: 105px !important;
            line-height: 105px !important;
        }
        .el-color-picker{
            vertical-align: middle
        }
        .el-input{
            width:80%;
        }

    </style>
@stop
@section("javascript")
    <link href="https://cdn.bootcss.com/wangEditor/3.0.8/wangEditor.min.css" rel="stylesheet">
    <script src="https://cdn.bootcss.com/wangEditor/3.0.8/wangEditor.min.js" type="text/javascript"></script>

    <script>


        let question ={!! $question !!}, img ={!! $img !!}, tip ={!! $tip !!},mini='{!! $mini !!}';

        let vm = new Vue({
            el: '#app',
            data: {
                question: question.system_tip,
                img: img,
                tip: tip.system_tip,
                mini: {
                    navigation_bar_color: mini.navigation_bar_color|| '#8ddef8',
                    banner_url: mini.banner_url,
                    common_color: mini.common_color || '#8ddef8',
                    mini_type:mini.mini_type|| "1",
                },
                mini_rules: {
                    navigation_bar_color: [
                        {required: true, message: '请选择导航条色调', trigger: 'change'},
                    ],
                    mini_type:[{
                        required:true
                    }],

                    banner_url: [
                        {required: true, message: '请上传图片', trigger: 'change'}
                    ],
                    common_color: [
                        {required: true, message: '请小程序整体色调', trigger: 'change'}
                    ],

                }


            },

            methods: {
                subImg: function () {
                    if (!this.img.length) {
                        this.$message.error('请上传图片再提交');
                        return false;
                    }
                    let data = {data: this.img, 'type': "supporting_url"};
                    this.commonAjax(data);
                },

                subTip: function () {

                    if (!this.tip) {
                        this.$message.error('请输入退订政策');
                        return false;
                    }
                    let data = {system_tip: this.tip, type: 'system_tip'};
                    this.commonAjax(data);
                },

                subQuestion: function () {

                    if (!this.question) {
                        this.$message.error('请输入常见问题描述');
                        return false;
                    }
                    let data = {system_tip: this.question, type: 'question_tip'};
                    this.commonAjax(data);

                },
               sbuMiniSetting:function(){
                   if (!(this.mini.navigation_bar_color && this.mini.common_color && this.mini.banner_url && this.mini.mini_type)){
                       this.$message.error('请完整输入小程序通用信息');
                       return false;
                   }
                    this.$http.post("{{route('mini_setting')}}",this.mini).then(res=>{
                        this.$message.success('操作成功');
                        setTimeout("window.reload()",1000)
                    });
               },

                commonAjax: function (data) {

                    this.$http.post('{{route('experience_settings.store')}}', data).then(res => {
                        this.$message.success('操作成功');
                    })
                }


            }
        })


    </script>
@stop