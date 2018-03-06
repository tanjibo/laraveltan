@extends("layouts.main")


@section("content")
    <div class="content" id="app">

        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">@if(isset($model))修改文章@else 添加文章 @endif</h3>
            </div>
            <!-- /.box-header -->
            <el-form ref="form" :model="form" label-width="80px">
                <div class="box-body">
                    <div class="box-group" id="accordion">
                        <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                        <article_child  :num="articleNum"
                                       :child.sync="form"></article_child>

                        <div class="box-footer">
                            <el-form-item>
                                @if(isset($model))
                                    <el-button type="primary"  @click="submitForm('edit')">保存数据</el-button>
                                @else
                                    <el-button type="primary"  @click="submitForm('add')">添加数据</el-button>

                                @endif

                            </el-form-item>
                        </div>
                    </div>
                </div>
            </el-form>
            <!-- /.box-body -->
        </div>
    </div>
@endsection

@section('css')
    <style>
        .el-form-item__content .inline {
            width: 80px !important;
        }
    </style>
@endsection
@section('javascript')
    <script>

        let data = {!! isset($model)?$model->toJson():'[]' !!};
            let vm = new Vue({
            el: '#app',
            data: {
                articleNum:0,
                form: {
                    title: data.title?data.title:'',
                    author: data.author?data.author:"",
                    desc: data.desc?data.author:'',
                    content: data.content?data.content:'',
                    cover_img:data.cover_img?data.cover_img:'',
                    type:'0'
                },
            },

            methods: {
                submitForm(type='edit'){
                    if(!(this.form.cover_img && this.form.content && this.form.title && this.form.author)){
                        this.$message.error('请完成文章信息');return false;
                    }

                    if(type=='edit'){
                        this.form._method = 'PUT';
                    }


                    let url=type=='edit'?laroute.route('experience_article.update',{'experience_article':data['id']}):'{{route("experience_article.store")}}';
                    this.$http.post(url, this.form).then(res => {
                        swal({
                            title: "温馨提示！",
                            text: "保存成功。",
                            timer: 1000,
                            type: "success",
                            showConfirmButton: false,
                        }).catch(res=>{
                            window.location.href='{{route('experience_article.index')}}'
                        });
                    })
                },

            }

        })

    </script>

@endsection