@extends("layouts.main")


@section("content")
    <div class="content" id="app">

        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">添加爬虫</h3>
            </div>
            <!-- /.box-header -->
            <el-form label-width="80px">
                <div  class="box-body">
                    <div class="box-group" id="accordion">
                        <div class="panel box box-primary">

                            <div class="panel-collapse collapse in">
                                <div class="box-body">
                                    <el-form-item label="微信文章地址">
                                        <el-input v-model="wechat_url"></el-input>
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
    </style>
@endsection
@section('javascript')
    <script>


        let vm = new Vue({
            el: '#app',
            data: {
                wechat_url:"",
                fullscreenLoading: false
            },

            methods: {

                submitForm(){

                       if(!this.wechat_url){
                           this.$message.error('请输入地址');
                           return ;
                       }

                    let url ='{{route("experience_wechat_fetcher.store")}}';

                    const loading = this.$loading({
                        lock: true,
                        text: '正在抓取微信文章,请不要关闭或者刷新页面.....',
                        spinner: 'el-icon-loading',
                        background: 'rgba(0, 0, 0, 0.7)'
                    });
                    this.$http.post(url, {wechat_url:this.wechat_url}).then(res => {
                        swal({
                            title: "温馨提示！",
                            text: "保存成功。",
                            timer: 1000,
                            type: "success",
                            showConfirmButton: false,
                        }).catch(res => {
                            window.location.href='{{route('experience_wechat_fetcher.index')}}'
                        });
                    })
                },

            }

        })

    </script>

@endsection