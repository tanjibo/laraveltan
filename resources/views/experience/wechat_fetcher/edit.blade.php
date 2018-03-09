@extends("layouts.main")


@section("content")
    <div class="content" id="app">

        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><strong style="color:red;">【{{str_limit($model->title,20,'....')}}】</strong>选择文章类型</h3>
            </div>
            <!-- /.box-header -->
            <el-form label-width="100px">
                <div  class="box-body">
                    <div class="box-group" id="accordion">
                        <div class="panel box box-primary">

                            <div class="panel-collapse collapse in">
                                <div class="box-body">
                                    <el-form-item label="选择文章类型">
                                        <el-radio  v-model="type" label="1">发现栏目</el-radio>
                                        <el-radio  v-model="type" label="0">三舍栏目</el-radio>
                                    </el-form-item>
                                    <el-form-item v-if="type==1" label="选择子文章">
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
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <el-form-item>

                                <el-button type="primary" @click="submitForm()">保存数据</el-button>
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
        .el-select .el-select__tags{
            width:100%;
        }
        .el-select__tags-text{
            white-space: nowrap;
            text-overflow: ellipsis;
            overflow: hidden;
            max-width:120px !important;
            display: inline-block;
        }
        .el-select-dropdown__item>span{
            max-width:120px !important;
            white-space: nowrap;
            text-overflow: ellipsis;
            overflow: hidden;
            display:inline-block;
            overflow: hidden;
        }

    </style>
@endsection
@section('javascript')
    <script>

        let model = {!! isset($model)?$model->toJson():'[]' !!};
        let options={!! isset($other)?$other->toJson() :'[]'!!};
        let vm = new Vue({
            el: '#app',
            data: {
               article:model,
                options:options,
                others:[],
                id:model.id,
                type:'1',
            },

            methods: {

                submitForm(type = 'edit'){
                    let url= laroute.route('experience_wechat_fetcher.update', {'experience_wechat_fetcher': model['id']})
                    let formDataJson={
                        id:this.id,
                        type:this.type,
                        _method:"PUT"
                    }
                    if(this.others.length){
                        formDataJson.others=this.others;
                    }

                    this.$http.post(url, formDataJson).then(res => {
                        swal({
                            title: "温馨提示！",
                            text: "保存成功。",
                            timer: 1000,
                            type: "success",
                            showConfirmButton: false,
                        }).catch(res => {
                            let url=(this.type==1?laroute.route('experience_more_article.index'):laroute.route('experience_article.index'));
                            window.location.href=url;
                        });
                    })
                },

            }

        })

    </script>

@endsection