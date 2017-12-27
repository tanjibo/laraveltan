@extends('layouts.main')

@section('content')
    <div class="content" id="app">
        <div class="box box-slider">
            <el-form ref="form" :rules="rules" :model="form" label-width="80px">
                <div class="box-header">
                    <h4>添加展示</h4>
                </div>
                <div class="box-body">
                    <div class="col-md-8 col-md-offset-2">
                        <el-form-item label="展品名称" prop="name">
                            <el-input type="name" v-model="form.name"></el-input>
                        </el-form-item>
                        <el-form-item label="属性" prop="attr">
                            <el-input type="name" v-model="form.attr"></el-input>
                        </el-form-item>

                        <el-form-item label="简介" prop="desc">
                            <el-input type="name" v-model="form.desc"></el-input>
                        </el-form-item>
                        <el-form-item label="描述" prop="introduction">
                            <editor :id="'id2'" :editdata.sync="form.introduction"></editor>
                        </el-form-item>
                        <el-form-item label="图片" prop="cover">
                            <uploader-single :imgurl.sync="form.cover"></uploader-single>
                        </el-form-item>


                    </div>

                </div>
                <div class="box-footer col-md-12">
                    <el-form-item>

                        <el-button type="primary" @click="sub('form')"> @if(isset($art))更新@else立即创建 @endif</el-button>
                    </el-form-item>
                </div>
            </el-form>

        </div>
    </div>
@stop
@section('javascript')
    <link href="https://cdn.bootcss.com/wangEditor/3.0.8/wangEditor.min.css" rel="stylesheet">
    <script src="https://cdn.bootcss.com/wangEditor/3.0.8/wangEditor.min.js" type="text/javascript"></script>

    <script>

        let model ={!! isset($art)?$art:'""'!!};
        let vm = new Vue({
            el: "#app",
            data: {

                form: {
                    name: model.name ? model.name : '',
                    desc: model.desc ? model.desc : '',
                    cover: model.cover ? model.cover : '',
                    attr: model.attr ? model.attr : '',
                    introduction: model.introduction ? model.introduction : '',
                },
                rules: {
                    name: [
                        {required: true, message: '请输入展示名称', trigger: 'blur'},
                        {min: 3, max: 20, message: '长度在 3 到 20个字符', trigger: 'blur'}
                    ],
                    cover: [
                        {required: true, message: '请上传图片', trigger: 'change'}
                    ],
                    attr: [
                        {required: true, message: '请输入属性', trigger: 'change'}
                    ],
                    introduction: [
                        {required: true, message: '请输入描述', trigger: 'change'}
                    ],
                    desc: [
                        {required: true, message: '请填写简介', trigger: 'blur'}
                    ]
                }
            },
            methods: {
                sub(rules){

                    let url = '';
                    if (model) {
                        url = '{{route('art.update',isset($art)?$art:'')}}';
                        this.form._method = "PUT";
                    }
                    else {
                        url = '{{route('art.store')}}';

                    }
                    this.$refs[rules].validate((valid) => {
                        if (!valid)return false;

                        this.$http.post(url, this.form).then(res => {
                            window.location.href="{{route('art.index')}}";
                        }).catch(err=>{
                            this.$message.error(err.message)
                        })

                    })


                }
            }
        })
    </script>
@stop