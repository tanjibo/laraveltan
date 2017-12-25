@extends('layouts.main')

@section('content')
    <div class="content" id="app">
        <div class="box box-solid">
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
        .el-upload-list--picture-card .el-upload-list__item {
            width: 90px !important;
            height: 105px !important;

        }

        .el-upload--picture-card {
            width: 90px !important;
            height: 105px !important;
            line-height: 105px !important;
        }
    </style>
@stop
@section("javascript")
    <link href="https://cdn.bootcss.com/wangEditor/3.0.8/wangEditor.min.css" rel="stylesheet">
    <script src="https://cdn.bootcss.com/wangEditor/3.0.8/wangEditor.min.js" type="text/javascript"></script>

    <script>


        let question ={!! $question !!}, img ={!! $img !!}, tip ={!! $tip !!};

        let vm = new Vue({
            el: '#app',
            data: {
                question:question.system_tip,
                img: img,
                tip: tip.system_tip,

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

                commonAjax: function (data) {

                    this.$http.post('{{route('experience_settings.store')}}', data).then(res => {
                         this.$message.success('操作成功');
                    })
                }


            }
        })


    </script>
@stop