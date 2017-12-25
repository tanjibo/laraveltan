@extends('layouts.main')
@section('content')

    <div class="content" id="app">

        <div class="box box-solid">
            <div class="box-header with-border"><h3 class="box-title">锁定房间</h3></div>
            <el-form :model="form">
                <div class="box-body">
                    <div class="box-group" id="accordion">
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
                                    <lock-date :lockdate.sync="form.selfLockDate"></lock-date>
                                </div>
                            </div>
                        </div>
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
                                    <lock-date :lockdate.sync="form.dateList"></lock-date>

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
    <script type="text/javascript">


        let lockDate ={!! $lockDate !!}, room_id ={!! $id !!};
        console.log(lockDate)
        var vm = new Vue({
            el: '#app',
            data: {
                form: {
                    selfLockDate: [], //自己的锁定时间
                    dateList: [], //动态生成的
                    room_id: room_id,
                    dateSelect: '',
                    pickerOptions2: {
                        onPick(date){
                            if (date.minDate && date.maxDate) {
                                vm.$http.post('{{route('experience_rooms.makeDate')}}', {
                                    startDate: date.minDate,
                                    endDate: date.maxDate
                                }).then(res => {
                                    vm.form.dateList = res;
                                })
                            }
                        }
                    }


                }

            },
            mounted(){
                let date=lockDate['self'];
                for(let item in date ){
                    this.form.selfLockDate.push(date[item])
                }
            },


            methods: {


                sub () {
                    this.$http.post('{{route('experience_rooms.lockDate',$id)}}', {
                        lockDate: this.form.dateList,
                        selfDate: this.form.selfLockDate,
                        room_id: this.form.room_id
                    }).then(res => {
                           reload();
                    })

                },

            }
        })
    </script>

@stop


