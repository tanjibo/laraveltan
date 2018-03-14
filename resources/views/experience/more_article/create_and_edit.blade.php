@extends("layouts.main")


@section("content")
    <div class="content" id="app">

        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">@if(isset($model))修改文章@else 添加文章 @endif</h3>
                <el-button type="success" size="mini" class="pull-right" @click="addArticle">添加子文章</el-button>
            </div>
            <!-- /.box-header -->
            <el-form label-width="80px">
                <div  class="box-body">
                    <div class="box-group" id="accordion">

                        <article_child v-on:delchild="delChild" v-for="(child,index) in formData" :num="index"
                                       :child.sync="child"></article_child>
                        <el-form-item  v-if="WechatArticleLen" label="子文章">
                            <el-select  style="max-width:100%" v-model="others" multiple placeholder="请选择">
                                <el-option
                                        size="medium"
                                        v-for="item in options"
                                        :key="item.id"
                                        :label="item.title"
                                        :value="item.id">
                                </el-option>
                            </el-select>
                        </el-form-item>
                        <div class="box-footer">
                            <el-form-item>
                                @if(isset($model))
                                    <el-button type="primary" @click="submitForm('edit')">保存数据</el-button>
                                @else
                                    <el-button type="primary" @click="submitForm('add')">添加数据</el-button>

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
        let formDataTpl = [
            {
                title: '',
                author: '',
                desc: '',
                content: '',
                type: '1',
            }];
        let data = {!! isset($model)?$model->toJson():'formDataTpl' !!};
        let options={!! isset($other)?$other->toJson() :'[]'!!};
        let WechatArticleLen={!! isset($other)?count($other):0 !!};
        let vm = new Vue({
            el: '#app',
            data: {
                formData: data,
                options:options,
                others:[],
                WechatArticleLen:WechatArticleLen
            },

            methods: {
                addArticle(){
                    let form = {
                        title: '',
                        author: '',
                        desc: '',
                        content: '',
                        type: '2'
                    };
                    this.formData.push(form);
                    console.log(this.formData)
                },
                delChild(val){
                    let params = this.formData[val];
                    console.log(val)
                    this.formData.splice(val, 1);
                    if (!params.id) return;
                    delModal(() => {

                        params._method = "DELETE";
                        this.$http.post(laroute.route('experience_more_article.destroy', {'experience_more_article': params.id}), params).then(res => {
                            swal({
                                title: "温馨提示！",
                                text: "删除成功。",
                                timer: 1000,
                                type: "success",
                                showConfirmButton: false,
                            }).catch(res => {
                                window.location.reload();
                            });
                        })
                    })
                },
                submitForm(type = 'edit'){
                    for (let child of this.formData) {
                        if (!child.title || !child.author || !child.content) {
                            this.$message.error('请输入完整信息');
                            return;
                        }
                    }
                    let formDataJson = {};
                    formDataJson.data = this.formData;
                    if (type == 'edit') {
                        formDataJson._method = "PUT"
                    }

                    formDataJson.others=this.others
                    let url = type == 'edit' ? laroute.route('experience_more_article.update', {'experience_more_article': data[0]['id']}) : '{{route("experience_more_article.store")}}';

                  console.log(url,type)
                    this.$http.post(url, formDataJson).then(res => {
                        swal({
                            title: "温馨提示！",
                            text: "保存成功。",
                            timer: 1000,
                            type: "success",
                            showConfirmButton: false,
                        }).catch(res => {
                            window.location.href='{{route('experience_more_article.index')}}'
                        });
                    })
                },

            }

        })

    </script>

@endsection