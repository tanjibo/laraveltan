@extends("layouts.main")

@section("content")
    <div class="content" id="app">
        <section>
            <div class="callout callout-danger">
                <h4>为什么要设置?</h4>
                <p>我们采用自己的服务器与微信进行对接，意味着用户与公众号的交互将会发送到我们自己的服务器，所以可以自己设置一些自动回复话语.</p>
            </div>
        </section>

        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"> 微信公众号设置</h3>
            </div>
            <!-- /.box-header -->
            <el-form ref="form" :model="form" label-width="80px">
                <div class="box-body">

                    <div class="panel box box-primary">

                        <div class="panel-collapse collapse in">
                            <div class="box-body">


                                <el-form-item label="默认欢迎语">
                                    <editor :id="'editor1'" :editdata.sync="form.default_welcome"></editor>
                                </el-form-item>

                                <el-form-item label="推荐成功欢迎语">
                                    <editor :id="'editor2'" :editdata.sync="form.be_recommend_welcome"></editor>

                                </el-form-item>

                                <el-form-item label="自动回复">
                                    <editor :id="'editor3'" :editdata.sync="form.auto_reply_welcome"></editor>

                                </el-form-item>


                                <el-form-item label="正文内容">
                                    <new-quill-editor :id="'1'" :editdata.sync="form.menu_json"></new-quill-editor>
                                </el-form-item>


                            </div>
                        </div>
                    </div>

                </div>

                <div class="box-footer">
                    <el-form-item>
                        @if(isset($model))
                            <el-button type="primary" @click="submitForm('edit')">保存数据</el-button>
                        @else
                            <el-button type="primary" @click="submitForm('add')">添加数据</el-button>

                        @endif

                    </el-form-item>
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
    <link href="https://cdn.bootcss.com/wangEditor/3.0.8/wangEditor.min.css" rel="stylesheet">
    <script src="https://cdn.bootcss.com/wangEditor/3.0.8/wangEditor.min.js" type="text/javascript"></script>
    <script>
        //微信活动表  每一期活动

        let default_welcome = "欢迎光临<a href=\"https://weidian.com/s/1140693382\">了如三舍</a><br/>\
        在这里你可以搜索商品,查看订单,也将定期收到我们的精选内容\
        可以先输入这些关键词看看效果\
        <br/>【买】：进入店铺\
        <br/>【推】：查看店主推荐\
        <br/>【订】：查看自己的订单",

            be_recommend_welcome = "您的好友「{:friend}」通过您分享的链接{:name}活动，您额外获得一个抽奖码\
        「{:code}」<br/>\
         「{:userCenterUrl}」 查看您的抽奖码页面邀请好友报名获取额外抽奖码，活动规则具体查看抽奖码页面或者文章详情<br/>\
       「{:numberList}」查看抽奖码个数排行榜！";


        let data = {!! isset($model)?$model->toJson():'[]' !!},
            vm = new Vue({
                el: '#app',
                data: {
                    form: {

                        default_welcome: data.default_welcome || default_welcome,
                        be_recommend_welcome: data.be_recommend_welcome || be_recommend_welcome,
                        auto_reply_welcome: data.auto_reply_welcome || '感谢您的留言,请稍等。。。',
                        menu_json:data.menu_json
                    },

                },


                methods: {
                    submitForm(){

                        if (!(this.form.default_welcome && this.form.be_recommend_welcome && this.form.auto_reply_welcome && this.form.menu_json)) {
                            this.$message.error('请完成设置');
                            return false;
                        }




                     let url='{{route("official_account_setting.store")}}';
                        this.$http.post(url, this.form).then(res => {
                            swal({
                                title: "温馨提示！",
                                text: "保存成功。",
                                timer: 1000,
                                type: "success",
                                showConfirmButton: false,
                            }).catch(res => {
                                window.location.href = laroute.route('official_activity.index')
                            });
                        })
                    },


                }

            })

    </script>
@endsection