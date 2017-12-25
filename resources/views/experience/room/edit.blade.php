@extends("layouts.main")

@section("content")
    <div class="content" id="app">

        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">[{{$model->name}}]简介</h3>
            </div>
            <!-- /.box-header -->
            <el-form ref="form" :model="form" label-width="80px">
                <div class="box-body">
                    <div class="box-group" id="accordion">
                        <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                        <div class="panel box box-primary">
                            <div class="box-header with-border">
                                <h4 class="box-title">
                                    <a data-toggle="collapse">
                                        房间信息
                                    </a>
                                </h4>
                            </div>
                            <div class="panel-collapse collapse in">
                                <div class="box-body">

                                    <el-form-item label="房间名称">
                                        <el-input v-model="form.name"></el-input>
                                    </el-form-item>

                                    <el-form-item label="价格">
                                        <el-input type="number" v-model="form.price"></el-input>
                                    </el-form-item>

                                    <el-form-item label="配置">
                                        <el-input v-model="form.attach" placeholder="多个配置请使用英文','隔开"></el-input>
                                    </el-form-item>

                                    <el-form-item label="介绍">
                                        <editor :id="'editor1'" :editdata.sync="form.intro"></editor>
                                    </el-form-item>

                                    <el-form-item label="设计理念">
                                        <editor :id="'editor2'" :editdata.sync="form.design_concept"></editor>

                                    </el-form-item>

                                    <el-form-item label="排序">
                                        <el-input v-model="form.sort"></el-input>
                                    </el-form-item>

                                    <el-form-item label="类型">
                                        <el-radio-group v-model="form.type">
                                            <el-radio label="2">全院</el-radio>
                                            <el-radio label="1">普通</el-radio>
                                        </el-radio-group>
                                    </el-form-item>

                                </div>
                            </div>
                        </div>
                        <div class="panel box box-danger">
                            <div class="box-header with-border">
                                <h4 class="box-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                                        图片
                                    </a>
                                </h4>
                            </div>
                            <div  class="panel-collapse collapse in">
                                <div class="box-body">
                                    <el-form-item label="轮播图片">
                                        <uploader :imgurl.sync="form.sliders"></uploader>
                                    </el-form-item>

                                    <el-form-item label="配套设施">
                                        <el-checkbox-group v-model="form.attach_url">
                                            <el-checkbox v-for="attach in form.attach_url_arr" :label="attach.id"
                                                         :key="attach.id"><img :src="attach.url"
                                                                               style="width:50px;height:50px;">
                                            </el-checkbox>
                                        </el-checkbox-group>
                                    </el-form-item>

                                    <el-form-item label="封面图片">
                                        <uploader-single :imgurl.sync="form.cover"></uploader-single>
                                    </el-form-item>
                                </div>
                            </div>
                        </div>
                        <div class="panel box box-success">
                            <div class="box-header with-border">
                                <h4 class="box-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                                        房间每日价格表
                                    </a>
                                </h4>
                            </div>
                            <div  class="panel-collapse collapse in">
                                <div class="box-body">
                                    <special-price :specialprice.sync="form.specialPrice"></special-price>
                                </div>
                            </div>
                        </div>
                        <div class="panel box box-info">
                            <div class="box-header with-border">
                                <h4 class="box-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
                                        房间价格选择
                                    </a>
                                </h4>
                            </div>
                            <div  class="panel-collapse collapse in">
                                <div class="box-body">
                                    <el-form-item label="价格段">
                                        <el-input style="width:200px;" v-model="defautPrice" placeholder="价格">
                                        </el-input>
                                        <el-input style="width:200px;" v-model="defaultType" placeholder="淡季、节假日、旺季">
                                        </el-input>

                                        <el-date-picker
                                                v-model="dateSelect"
                                                type="daterange"
                                                range-separator="至"
                                                start-placeholder="开始日期"
                                                end-placeholder="结束日期"
                                        >
                                        </el-date-picker>
                                        <el-button @click="makePrice" type="primary" plain>生成价格</el-button>

                                    </el-form-item>
                                    <special-price :specialprice.sync="createSpecialPrice"></special-price>

                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <el-form-item>
                                <el-button type="primary" @click="submitForm('form')">更新房间数据</el-button>

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
    <link href="https://cdn.bootcss.com/wangEditor/3.0.8/wangEditor.min.css" rel="stylesheet">
    <script src="https://cdn.bootcss.com/wangEditor/3.0.8/wangEditor.min.js" type="text/javascript"></script>
    <script>


        //iCheck for checkbox and radio inputs


        let data = {!! $model->toJson() !!}, sliders = {!! $model->sliders->toJson() !!};
        let attachUrl ={!! $attachUrl !!};
        let specialPrice = {!! $specialPrice->toJson() !!}
      console.log(data.attach_url)
            let
        vm = new Vue({
            el: '#app',
            data: {
                defautPrice: 12000,
                defaultType: '淡季',
                dateSelect: [],
                createSpecialPrice:[],
                form: {
                    name: data.name,    //房间名称
                    price: data.price,   //价格
                    attach: data.attach, //配置
                    intro: data.intro,
                    design_concept: data.design_concept,
                    sort: data.sort,
                    type: data.type,
                    sliders: sliders,
                    attach_url: data.attach_url?data.attach_url:[],
                    attach_url_arr: attachUrl,
                    cover: data.cover,
                    specialPrice: specialPrice

                },

            },

            methods: {
                submitForm(){
                   this.form._method = 'PUT';
                   this.form.newSpecialPrice=this.createSpecialPrice;
                    this.$http.post('{{route('experience_rooms.update',$model)}}', this.form).then(res => {
                        swal({
                            title: "温馨提示！",
                            text: "成功更新房间内容。",
                            timer: 1000,
                            type: "success",
                            showConfirmButton: false,
                        }).catch(res=>{
                             window.location.href='{{route('experience_rooms.index')}}'
                        });
                    })
                },
                makePrice(){
                    let data = {
                        type: this.defaultType,
                        price: this.defautPrice,
                        dateSelect: this.dateSelect
                    }

                    this.$http.post('{{route('experience_rooms.makePrice',$model)}}', data).then(res => {

                       this.createSpecialPrice=res;

                    })

                }
            }

        })

    </script>
@endsection