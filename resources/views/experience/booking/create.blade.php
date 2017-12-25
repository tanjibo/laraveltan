@extends("layouts.main")

@section("content")
    <section class="content" id="app">

        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">在线预约</h3>
            </div>
            <div class="box-body">
                <section class="col-md-8 col-md-offset-2">

                    <el-form ref="form" :model="form" label-width="120px">
                        <el-form-item label="时间">

                            <el-date-picker
                                    v-model="form.dateArr"
                                    type="daterange"
                                    range-separator="至"
                                    start-placeholder="开始日期"
                                    end-placeholder="结束日期"
                                    clearable='clearable'
                                    :picker-options="datePickDisabledDate">
                            </el-date-picker>

                        </el-form-item>
                        <el-form-item label="还可以预约">
                                <el-checkbox-group v-model="form.rooms">
                                    <el-checkbox v-for="item in leftCheckinRoom" :label="item.id" name="rooms">@{{ item.name }}</el-checkbox>
                                </el-checkbox-group>
                        </el-form-item>

                        <el-form-item label="客户名称">
                            <el-input v-model="form.customer"></el-input>
                        </el-form-item>
                        <el-form-item label="手机">
                            <el-input v-model="form.mobile"></el-input>
                        </el-form-item>
                        <el-form-item label="性别">
                            <el-radio-group v-model="form.gender">
                                <el-radio label="1">男</el-radio>
                                <el-radio label="2">女</el-radio>
                            </el-radio-group>
                        </el-form-item>

                        <el-form-item label="人数">

                            <el-input-number v-model="form.people" :min="1" :max="25" label="人数"></el-input-number>
                        </el-form-item>
                        <el-form-item label="特别要求">
                            <el-input type="textarea" v-model="form.requirements"></el-input>

                        </el-form-item>
                        <el-form-item label="备注(100字以内)">
                            <el-input type="textarea" v-model="form.remark"></el-input>
                        </el-form-item>
                        <el-form-item size="large">
                            <el-button type="primary" @click="onSubmit">立即创建</el-button>
                            <el-button>取消</el-button>
                        </el-form-item>
                    </el-form>
                </section>


            </div>
        </div>
        </div>


    </section>
@endsection
@section('css')
    <style>
        .description-block {
            min-height: 80px;
            height: auto;
        }

        .description-block .description-text {
            margin-bottom: 15px;
        }

        .description-block .description-header {
            font-weight: 400;
        }

        .bb {
            border-bottom: 1px solid #d8dce5;

        }

        .bl {
            border-left: 1px solid #d8dce5;
        }

        .el-carousel__item h3 {
            color: #475669;
            font-size: 14px;
            opacity: 0.75;
            line-height: 150px;
            margin: 0;
        }

        .el-carousel__indicators {
            display: none;
        }

        .el-carousel__container {
            position: relative;
        }

        .el-carousel__item:nth-child(2n) {
            background-color: #99a9bf;
        }

        .roomInfo {
            position: absolute;
            z-index: 100;
            left: 20px;
            top: 20px;
        }

        .roomInfo span {
            display: block;
            font-size: 16px;
        }

        .el-carousel__item:nth-child(2n+1) {
            background-color: #d3dce6;
        }

    </style>
@stop

@section('javascript')
    <script src="https://cdn.bootcss.com/moment.js/2.18.1/moment.min.js"></script>
    <script>

        var vm = new Vue({
            el: '#app',
            data: {
                datePickDisabledDate: {
                    disabledDate(time) {
                        var date = filterDate(time);
                        if (vm.disabledDateArr.indexOf(date) > -1)return true;
                       // if (time.getTime() < Date.now() - 8.64e7)return true;
                        //if (time.getTime() >= (Date.now() + 3600 * 24 * 6 * 30 * 1000)) return true;
                    },
                    onPick(date){

                        let checkin = filterDate(date.minDate);
                        vm.disabledDateArr = [];
                        if (!date.maxDate) {
                            vm.$http.post('{{route('experience_bookings.calendarInit',$model)}}', {checkin: checkin}).then(res => {
                                for (let i in res) {
                                    vm.disabledDateArr.push(res[i]);
                                }
                            })
                        } else {
                            let checkout = filterDate(date.maxDate);
                            vm.leftCheckinRoom = [];
                            vm.$http.post('{{route('experience_bookings.leftCheckinRoom',$model)}}', {
                                checkin: checkin,
                                checkout: checkout
                            }).then(res => {
                                vm.leftCheckinRoom=res;
                            })
                        }

                    }
                },
                disabledDateArr: [],
                leftCheckinRoom: [],
                clearable: true,
                form: {
                    rooms:[],
                    mobile:'',
                    customer:'',
                    people:'',
                    gender:'',
                    requirements:'',
                    remark:'',
                    dateArr:{},
                    checkin:'',
                    checkout:'',
                    pay_mode:0,
                    source:'LrssAdmin'
                }

            },
            mounted(){
                this.$http.post('{{route('experience_bookings.calendarInit',$model)}}').then(res => {
                    for (let i in res) {
                        this.disabledDateArr.push(res[i]);
                    }
                })
            },
            methods: {
                onSubmit(){
                  this.form.checkin=filterDate(this.form.dateArr[0]);
                  this.form.checkout=filterDate(this.form.dateArr[1]);
                  this.form.rooms.push({{$model->id}})
                    this.$http.post("{{route('experience_bookings.store')}}",this.form).then(res=>{
                       reload();
                    })
                },


            }

        });


    </script>
@stop
