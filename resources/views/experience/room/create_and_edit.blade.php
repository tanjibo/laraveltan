@extends("layouts.main")

@section("content")
    <div class="content" id="app">

        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">@if(isset($model))[{{$model->name}}]简介@else 添加房间 @endif</h3>
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

                                    <el-form-item label="普通价格">
                                        <el-input type="number" v-model="form.price"></el-input>
                                    </el-form-item>
                                    <el-form-item label="休息日价格">
                                        <el-input type="number" v-model="form.playday_price"></el-input>
                                    </el-form-item>
                                    <el-form-item label="节假日价格">
                                        <el-input type="number" v-model="form.holiday_price"></el-input>
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
                                        <el-input v-model="@if(isset($model)) form.sort @else sorted @endif "></el-input>
                                    </el-form-item>

                                    <el-form-item label="类型">
                                        <el-radio-group v-model="form.type">
                                            <el-radio label="2">全院</el-radio>
                                            <el-radio label="1">普通</el-radio>
                                        </el-radio-group>
                                    </el-form-item>

                                    <el-form-item label="是否支持预付金">
                                        <el-switch v-model="form.is_prepay"></el-switch>
                                    </el-form-item>

                                    <el-form-item v-show="form.is_prepay" label-width="100px"   label="预付金百分比">
                                        <el-slider
                                                v-model="form.prepay_percent"
                                                :step="10"
                                                show-stops>
                                        </el-slider>
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
                        @if(isset($model))
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
                            <div class="panel-collapse collapse in">
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
                                                @change="makePrice"
                                        >
                                        </el-date-picker>

                                    </el-form-item>
                                    <special-price :specialprice.sync="createSpecialPrice"></special-price>

                                </div>
                            </div>
                        </div>
                        @endif
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
    <link href="https://cdn.bootcss.com/wangEditor/3.0.8/wangEditor.min.css" rel="stylesheet">
    <script src="https://cdn.bootcss.com/wangEditor/3.0.8/wangEditor.min.js" type="text/javascript"></script>
    <script>


        //iCheck for checkbox and radio inputs


        let data = {!! isset($model)?$model->toJson():'[]' !!}, sliders = {!! isset($model)? $model->sliders->toJson():'[]' !!};
        console.log(data.is_prepay)
        let attachUrl ={!! isset($attachUrl)?$attachUrl:'[]' !!};
        let specialPrice = {!! isset($specialPrice)?$specialPrice->toJson():'[]' !!}

            let vm = new Vue({
            el: '#app',
            data: {
                defautPrice: 12000,
                defaultType: '淡季',
                sorted:1,
                dateSelect: [],
                createSpecialPrice:[],
                form: {
                    name: data.name,    //房间名称
                    price: data.price,   //价格
                    attach: data.attach, //配置
                    playday_price:data.playday_price,
                    holiday_price:data.holiday_price,
                    intro: data.intro,
                    design_concept: data.design_concept,
                    'sort':data?data['sort']:1,
                    type: data.type?data.type:'1',
                    sliders: sliders,
                    attach_url: data.attach_url?data.attach_url:[],
                    attach_url_arr: attachUrl,
                    cover: data.cover,
                    specialPrice: specialPrice,
                    prepay_percent:data.prepay_percent?data.prepay_percent:100,
                    is_prepay:!!data.is_prepay

                },

            },
           watch:{
               sorted:function(){
                   this.form.sort=this.sorted;
               },
               'form.is_prepay':function(){
                  if(!this.form.is_prepay){
                      this.form.prepay_percent=100;
                  }
               }
           },
            methods: {
                submitForm(type='edit'){
                   if(!(this.form.cover && this.form.name && this.form.price && this.form.playday_price && this.form.holiday_price)){
                       this.$message.error('请完成房间信息');return false;
                   }

                   if(type=='edit'){
                       this.form._method = 'PUT';
                       this.form.newSpecialPrice=this.createSpecialPrice;
                   }


                   let url=type=='edit'?laroute.route('experience_rooms.update',{'experience_room':data['id']}):'{{route("experience_rooms.store")}}';
                    this.$http.post(url, this.form).then(res => {
                        swal({
                            title: "温馨提示！",
                            text: "保存成功。",
                            timer: 1000,
                            type: "success",
                            showConfirmButton: false,
                        }).catch(res=>{
                             window.location.href='{{route('experience_rooms.index')}}'
                        });
                    })
                },
                makePrice(){
                    let params = {
                        type: this.defaultType,
                        price: this.defautPrice,
                        dateSelect: this.dateSelect
                    }
                    let url=laroute.route('experience_rooms.makePrice',{experience_room:data['id']})
                    this.$http.post(url, params).then(res => {
                    
                        this.createSpecialPrice=this.createSpecialPrice.concat(res);
                       console.log(this.createSpecialPrice)
                    })

                }
            }

        })

    </script>
@endsection