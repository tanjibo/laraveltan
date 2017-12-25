@extends('layouts.main')
@section('content')

    <div class="content" id="app">

        <div class="box box-solid">
            <div class="box-header with-border"><h3 class="box-title">锁定房间</h3></div>
            <el-form :model="form">
                <div class="box-body">
                    <div class="box-group" id="accordion">
                        <div class="panel box box-info">
                            <div class="box-header with-border">
                                <h4 class="box-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
                                        添加锁定时间
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseFour" class="panel-collapse collapse in">
                                <div class="box-body">

                                    <el-form-item label="时间段">
                                        <el-date-picker
                                                v-model="form.dateSelect"
                                                type="daterange"
                                                range-separator="至"
                                                start-placeholder="开始日期"
                                                end-placeholder="结束日期"
                                                :picker-options="form.pickerOptions2"
                                        >
                                        </el-date-picker>

                                    </el-form-item>
                                    <tearoom-lock-time-select v-for="(item,key) in dateList" :number="key" v-on:del="delNewLock" :datelist.sync="item"
                                                              :schedule.sync="schedule"></tearoom-lock-time-select>


                                </div>
                            </div>
                        </div>

                        <div class="panel box box-success">
                            <div class="box-header with-border">
                                <h4 class="box-title">
                                    <a data-toggle="collapse">
                                        已有锁定时间
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseThree">
                                <div class="box-body">

                                    <tearoom-lock-time-select v-for="(item,key) in hasLock" :number="key" v-on:del="delItem" :datelist.sync="item"
                                                              :schedule.sync="schedule"></tearoom-lock-time-select>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="box-footer">
                    <el-form-item>
                        <el-button @click.prevent="sub" type="primary">锁定时间</el-button>
                    </el-form-item>
                </div>
            </el-form>

        </div>


    </div>
@stop
@section('javascript')
    <script>


        let vm = new Vue({
            el: '#app',
            data: {
                schedule: [],
                dateList: [],
                hasLock: [],

                form: {
                    tearoom_id: {{$id}},
                    pickerOptions2: {
                        onPick(date){
                            if (date.minDate && date.maxDate) {
                                let url = laroute.route('tearoom.makeDate');
                                vm.$http.post(url, {
                                    startDate: date.minDate,
                                    endDate: date.maxDate
                                }).then(res => {
                                    let data = res.data;
                                    console.log(data);
                                    vm.dateList = data.date;
                                    vm.schedule = data.schedule;
                                })
                            }
                        }
                    }
                }
            },
            mounted(){
                let url = laroute.route('tearoom.initGetLockDate', {tearoom: this.form.tearoom_id});

                this.$http.post(url, {'data': ""}).then(res => {
                    let data = res.data;

                    this.schedule = data.schedule;

                    this.hasLock = data.date;
                })
            },
            methods: {

                delItem(item){
                    console.log(item);
                   this.hasLock.splice(item,1)
                },
                delNewLock(item){
                    this.dateList.splice(item,1)
                },
                sub () {
                    this.$http.post('', {
                        lockDate: this.dateList,
                        hasLock: this.hasLock,
                        room_id: this.form.tearoom_id
                    }).then(res => {
                        reload();
                    })

                }
            }
        })
    </script>

@stop


