@extends('layouts.main')

@section('content')
    <div class="content" id="app">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">@if(!isset($tearoom))添加茶舍空间@else 修改茶舍空间@endif</h3>
            </div>

            <el-form ref="form" :model="form" label-width="80px">
                <div class="box-body">
                    <div class="box-group" id="accordion">
                        <div class="panel box box-primary">
                            <div class="box-header with-border">
                                <div class="box-title">
                                    <a data-toggle="collapse">
                                        空间信息
                                    </a>
                                </div>
                            </div>
                            <div class="panel-collapse collapse in">
                                <div class="box-body">

                                    <el-form-item label="房间名称">
                                        <el-input v-model="form.name"></el-input>
                                    </el-form-item>

                                    <el-form-item label="限制人数">
                                        <el-input type="number" v-model="form.limits"></el-input>
                                    </el-form-item>

                                    <el-form-item label="开始时间">
                                        <el-select v-model="form.start_point" placeholder="请选择活动区域">
                                            <el-option v-for="(item,key) in timeSchedule" :label="item"
                                                       :value="item.index"></el-option>
                                        </el-select>
                                    </el-form-item>

                                    <el-form-item label="结束时间">
                                        <el-select v-model="form.end_point" placeholder="请选择活动区域">
                                            <el-option v-for="(item,key) in timeSchedule" :label="item"
                                                       :value="key.index"></el-option>

                                        </el-select>
                                    </el-form-item>

                                    <el-form-item label="排序">
                                        <el-input type="number" v-model="form.sort"></el-input>
                                    </el-form-item>


                                    <el-form-item label="时间类型">
                                        <el-radio-group v-model="form.type">
                                            <el-radio label=0>独自</el-radio>
                                            <el-radio label=1>全部</el-radio>
                                        </el-radio-group>
                                    </el-form-item>
                                    <el-form-item label="状态">
                                        <el-radio-group v-model="form.status">
                                            <el-radio label=1>启用</el-radio>
                                            <el-radio label=0>关闭</el-radio>
                                        </el-radio-group>
                                    </el-form-item>

                                </div>
                            </div>
                        </div>

                        <div class="panel box box-primary">
                            <div class="box-header with-border">
                                <a class="box-title">图片</a>
                            </div>
                            <div class="panel-collapse collapse in">
                                <div class="box-body">
                                    <el-form-item label="封面图片">
                                        <uploader-single :imgurl.sync="form.image"></uploader-single>
                                    </el-form-item>
                                </div>
                            </div>
                        </div>

                        <div class="panel box box-primary">
                            <div class="box-header with-border">
                                <div class="box-title">
                                    <span>费用信息</span>
                                    <el-button type="primary" @click="addPrice" size="mini"
                                               icon="el-icon-plus"></el-button>
                                    <a>（每半小时为一个点，如一小时为2，一个半小时为3，两个小时为4）</a>
                                </div>
                            </div>
                            <div class="panel-collapse collapse in">
                                <div class="box-body">
                                    <tearoom-price :prices.sync="form.prices"></tearoom-price>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <el-form-item>
                                <el-button type="primary" @if(!isset($tearoom)) @click="submitForm('create')" @else @click="submitForm('edit')" @endif >@if(!isset($tearoom))添加数据@else 修改数据@endif</el-button>

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
    </style>
@stop
@section('javascript')
    <script type="text/javascript">
        let timeSchedule ={!! $timeSchedule !!};
        let tearoom ={!! isset($tearoom)?$tearoom:"[]" !!};
        let prices ={!! isset($prices)?$prices:'[]' !!};

        let vm = new Vue({
            el: '#app',
            data: {
                form: {
                    name: tearoom.name ? tearoom.name : '',
                    start_point: tearoom.start_point ? tearoom.start_point : 20,
                    end_point: tearoom.end_point ? tearoom.end_point : 42,
                    limits: tearoom.limits ? tearoom.limits : 4,
                    sort:tearoom.length?tearoom['sort']: 1,
                    image: tearoom.image ? tearoom.image : "",
                    type: tearoom.type ? tearoom.type : '0',
                    status: tearoom.status ? tearoom.status : '1',
                    prices: prices,
                },
                timeSchedule: timeSchedule,
                id:tearoom.id


            },

            methods: {

                addPrice() {
                    this.form.prices.push({
                        'durations': '',
                        'fee': '',
                        'status': '1'
                    })
                }
                ,
                submitForm(status,id='') {
                    if (!this.form.name) {
                        this.$message.error('请输入空间名称');
                        return;
                    }

                    if (!this.form.limits) {
                        this.$message.error('请输入限制人数');
                        return;
                    }
                    if (!this.form.image) {
                        this.$message.error('请输入封面图片');
                        return;
                    }

                    let url= status=='create'? laroute.route('tearoom.store'):laroute.route('tearoom.update',{'tearoom':this.id});
                    this.form._method="PATCH";
                    this.$http.post(url, this.form).then(res => {
                        reload();
                    })

                }
            }
        })
    </script>
@stop

