@extends('layouts.main')

@section('content')
    <div class="content" id="app">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">在线预约[{{$tearoom->name}}]</h3>
            </div>

            <el-form ref="form" :model="form" label-width="80px">
                <div class="box-body">
                    <div class="box-group" id="accordion">
                        <div class="panel box box-primary">
                            <div class="box-header with-border">
                                <div class="box-title">
                                    <a data-toggle="collapse">
                                        预约信息
                                    </a>
                                </div>
                            </div>
                            <div class="panel-collapse collapse in">
                                <div class="box-body">

                                    <el-form-item label="客户名称">
                                        <el-input v-model="form.customer"></el-input>
                                    </el-form-item>

                                    <el-form-item label="手机号码">
                                        <el-input type="number" v-model="form.mobile"></el-input>
                                    </el-form-item>

                                    <el-form-item label="性别">
                                        <el-radio-group v-model="form.gender">
                                            <el-radio :label="1">男</el-radio>
                                            <el-radio :label="2">女</el-radio>
                                        </el-radio-group>
                                    </el-form-item>
                                    <el-form-item label="预约人数">
                                        <el-input type="number" v-model="form.peoples"></el-input>
                                    </el-form-item>
                                    <el-form-item label="特别要求">
                                        <el-input type="textarea" placeholder="100字以内" v-model="form.tips"></el-input>
                                    </el-form-item>
                                    <el-form-item label="备注">
                                        <el-input type="textarea" placeholder="100字以内" v-model="form.remark"></el-input>
                                    </el-form-item>

                                </div>
                            </div>
                        </div>


                        <div class="panel box box-primary">
                            <div class="box-header with-border">
                                <div class="box-title">
                                    <span>预约时间</span>
                                </div>
                            </div>
                            <div class="panel-collapse collapse in">
                                <div class="box-body">
                                    <el-form-item>
                                        <el-date-picker
                                                v-model="date"
                                                type="date"
                                                placeholder="选择日期">
                                        </el-date-picker>
                                        <el-radio-group v-model="price_id">
                                            <el-radio v-for="item in prices" :label="item.id">@{{ item.durations/2 }}小时
                                            </el-radio>
                                        </el-radio-group>
                                    </el-form-item>

                                    <el-form-item>


                                        <el-radio-group v-model="form.start_point">
                                            <el-radio v-for="(item,key) in timeSchedule"
                                                      :label='item.point'
                                                      :disabled="!item.available">@{{ item.time }}</el-radio>
                                        </el-radio-group>


                                    </el-form-item>


                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <el-form-item>
                                <el-button type="primary" @if(!isset($tearoom)) @click="submit('create')"
                                           @else @click="submit('edit')" @endif >保存</el-button>

                            </el-form-item>
                        </div>
                    </div>
                </div>
            </el-form>

        </div>
    </div>

@stop
@section('css')
    <style>
        .box-title {
            font-size: 14px !important;
        }

        div.el-radio-group > label[tabindex="0"] {
            margin-left: 30px !important;
        }
    </style>
@stop
@section('javascript')
    <script type="text/javascript">
        let prices ={!! isset($tearoom->tearoom_prices)?$tearoom->tearoom_prices:'[]' !!};
        let timeSchedule =
                {!! $timeSchedule !!};
        var vm = new Vue({
            el: '#app',
            data: {

                form: {

                    customer: '',
                    mobile: '',
                    gender: 1,
                    peoples: "",
                    tips: "",
                    remark: "",
                    tearoom_id: {{$tearoom->id}},
                    start_point: '',
                },
                timeSchedule: timeSchedule,
                prices: prices,
                date: '{{date('Y-m-d')}}',
                price_id:{{$tearoom->tearoom_prices[0]->id}},


            },
            watch: {
                price_id(){
                    this.getSchedule();
                },
                date(){
                    this.getSchedule()
                }

            },
            mounted(){

                this.getSchedule();
            },
            methods: {
                getSchedule(){
                    let url = laroute.route("tearoom_booking.getInitTimeTable");
                    this.form.start_point = '';
                    this.$http.post(url, {price_id: this.price_id, date: this.date}).then(res => {
                        this.timeSchedule = res;
                    })
                },

                submit() {
                    if (!this.form.customer) {
                        this.$message.error('请输入客户名称');
                        return;
                    }

                    if (!/^(13[0-9]|14[0-9]|15[0-9]|17[0-9]|18[0-9])\d{8}$/ig.test(this.form.mobile)) {
                        this.$message.error('请输入正确格式的手机号码');
                        return;
                    }

                    if (!this.form.peoples) {
                        this.$message.error('请输入预约人数');
                        return;

                    }

                    if (!this.form.start_point) {
                        this.$message.error('请选择时间');
                        return false;
                    }

                    let url = laroute.route('tearoom_booking.store');
                    this.form.price_id = this.price_id;
                    this.form.date = this.date;
                    this.$http.post(url, this.form).then(res => {
                     window.location.href="{{route('tearoom_booking.index')}}"
                    })


                }
            }
        })


    </script>
@stop

