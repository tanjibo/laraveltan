@extends("layouts.main")

@section("content")
    <div class="content" id="app">
        <section>
            <div class="callout callout-danger">
                <h4>温馨提示</h4>
                <p>如果公众号设置完成后，欢迎语、推荐成功欢迎语、自动回复可以不用输入，如果一旦输入，你将更改公众号默认设置！</p>
            </div>
        </section>

        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"> 微信活动</h3>
            </div>
            <!-- /.box-header -->
            <el-form ref="form" :model="form" label-width="80px">
                <div class="box-body">

                    <div class="panel box box-primary">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <a data-toggle="collapse">
                                    活动简介
                                </a>
                            </h4>
                        </div>
                        <div class="panel-collapse collapse in">
                            <div class="box-body">

                                <el-form-item label="活动名称">
                                    <el-input v-model="form.name"></el-input>
                                </el-form-item>


                                <el-form-item label="欢迎语">
                                    <editor :id="'editor1'"   :editdata.sync="form.default_welcome"></editor>
                                </el-form-item>

                                <el-form-item label="推荐成功欢迎语">
                                    <editor :id="'editor2'"  :editdata.sync="form.be_recommend_welcome"></editor>

                                </el-form-item>

                                <el-form-item label="自动回复">
                                    <editor :id="'editor3'" :editdata.sync="form.auto_reply_welcome"></editor>

                                </el-form-item>


                                <el-form-item label="活动时间">
                                    <el-date-picker
                                            v-model="dateTimeSelect"
                                            type="daterange"
                                            start-placeholder="开始日期"
                                            end-placeholder="结束日期"
                                            :default-time="['00:00:00', '23:59:00']">
                                    </el-date-picker>
                                </el-form-item>

                                <el-form-item label="海报背景图">
                                    <uploader-single :uploadurl.sync="uploadUrl" :imgurl.sync="form.poster_base_img_url"></uploader-single>
                                </el-form-item>


                            </div>
                        </div>
                    </div>
                    @if(isset($model) && $model->qr_code_url)
                        <div class="panel box box-danger">
                            <div class="box-header with-border">
                                <h4 class="box-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                                        活动入口二维码
                                    </a>
                                </h4>
                            </div>
                            <div style="margin-top: 30px;" class="panel-collapse collapse in">

                                <el-form-item label-width="200">
                                    <img style="width:100px;height:100px;" src="{{$model->qr_code_url}}" alt="">
                                    <a  class="btn btn-sm btn-danger" href="{{route('official_activity.download', ['url'=>$model->qr_code_url])}}" plain>下载二维码 </a>
                                </el-form-item>
                            </div>
                        </div>

                        <div class="panel box box-danger">
                            <div class="box-header with-border">
                                <h4 class="box-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                                       分享朋友圈、朋友
                                    </a>
                                </h4>
                            </div>
                            <div style="margin-top: 30px;" class="panel-collapse collapse in">

                                <el-form-item label-width="200" label="分享标题">
                                    <el-input v-model="form.share.title"></el-input>
                                </el-form-item>


                                <el-form-item label-width="200" label="分享链接地址">
                                    <el-input  v-model="form.share.link_url" placeholder="这个地址为微信公众号的地址" v-model="form.share.link_url"></el-input>
                                </el-form-item>

                                <el-form-item label-width="200" label="分享描述">
                                    <el-input placeholder="不要超过255个字" type="textarea" v-model="form.share.desc"></el-input>
                                </el-form-item>


                                    <el-form-item label="缩略图">
                                        <uploader-single :uploadurl.sync="uploadUrl" :imgurl.sync="form.share.cover_img"></uploader-single>
                                    </el-form-item>



                            </div>
                        </div>
                    @endif


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

        let default_welcome='欢迎光临<a href="https://weidian.com/s/1140693382">了如三舍</a><br/>        在这里你可以搜索商品,查看订单,也将定期收到我们的精选内容        可以先输入这些关键词看看效果        <br/>【买】：进入店铺        <br/>【推】：查看店主推荐        <br/>【订】：查看自己的订单' +
                '<br/>[{:activity_name}活动请点击👇]<br/>[{:activity_home}]',

          be_recommend_welcome = "您的好友「{:friend}」通过您分享的链接{:name}活动，您额外获得一个抽奖码\
        「{:code}」<br/>\
         「{:userCenterUrl}」 查看您的抽奖码页面邀请好友报名获取额外抽奖码，活动规则具体查看抽奖码页面或者文章详情<br/>\
       「{:numberList}」查看抽奖码个数排行榜！";


        let data = {!! isset($model)?$model->toJson():'[]' !!},
         share = {!! isset($share)?$share->toJson():'[]' !!},
            vm = new Vue({
                el: '#app',
                data: {

                    dateTimeSelect: [data.start_time, data.end_time],
                    uploadUrl:"{{route('official_activity.upload')}}",
                    form: {
                        name: data.name,
                        default_welcome: data.default_welcome ||default_welcome,
                        be_recommend_welcome: data.be_recommend_welcome ||be_recommend_welcome,
                        auto_reply_welcome: data.auto_reply_welcome ||'感谢您的留言,请稍等。。。',
                        qr_code_url: data.qr_code_url,
                        start_time: data.start_time,
                        end_time: data.end_time,
                        poster_base_img_url:data.poster_base_img_url,
                        share:{
                            title:share.title,
                            desc:share.desc,
                            link_url:share.link_url,
                            cover_img:share.cover_img
                        }
                        // enmu:data.enmu
                    },

                },
                watch: {
                    dateTimeSelect: function (val) {
                        if (val.length == 2) {
                            this.form.start_time = val[0]
                            this.form.end_time = val[1]
                        } else {
                            this.form.start_time = this.form.end_time = ''
                        }

                    }
                },

                methods: {
                    submitForm(type = 'edit'){

                        if (!(this.form.start_time && this.form.name && this.form.end_time)) {
                            this.$message.error('请完成活动信息');
                            return false;
                        }

                        if (type == 'edit') {
                            this.form._method = 'PUT';
                        }

                        if(this.form.qr_code_url){
                            if(!(this.form.share.title && this.form.share.desc && this.form.share.link_url && this.form.share.cover_img)){
                                this.$message.error('分享内容不能为空');
                                return;
                            }
                        }


                        let url = type == 'edit' ? laroute.route('official_activity.update', {'official_activity': data['id']}) : '{{route("official_activity.store")}}';
                        this.$http.post(url, this.form).then(repo => {
                              let id=repo.id
                            console.log(id);
                            swal({
                                title: "温馨提示！",
                                text: "保存成功。",
                                timer: 1000,
                                type: "success",
                                showConfirmButton: false,
                            }).catch(res => {
                                console.log(res);
                               window.location.href = laroute.route('official_activity.index')
                            });
                        })
                    },


                }

            })

    </script>
@endsection