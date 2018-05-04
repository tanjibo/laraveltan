@extends("layouts.main")

@section("content")
    <section class="content" id="app">

        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">订单详情</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-3">
                        <!-- Widget: user widget style 1 -->
                        <div class="box box-widget widget-user-2">
                            <!-- Add the bg color to the header using any of the bg-* classes -->
                            <div class="widget-user-header bg-yellow">
                                <div class="widget-user-image">
                                    <img class="img-circle"
                                         src="{{$user->avatar?:icon($user->nickname)}}"
                                         alt="User Avatar">
                                </div>
                                <!-- /.widget-user-image -->
                                <div class="widget-user-username">{{$user->nickname}}</div>
                                <h5 class="widget-user-desc">电话:{{$user->mobile}}</h5>
                            </div>
                            <div class="box-footer no-padding">
                                <ul class="nav nav-stacked">
                                    <li><a href="{{route('user.user_all_order',$user)}}">总共订单数 <span
                                                    class="pull-right">{{$user->totalOrderNum()}}</span></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- /.widget-user -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-9">
                        <!-- Widget: user widget style 1 -->
                        <div class="box box-widget widget-user">
                            <!-- Add the bg color to the header using any of the bg-* classes -->
                            <div class="widget-user-header bg-aqua-active" style="padding:0;height:120px">
                                <el-carousel height="120px">
                                    <el-carousel-item v-for="item in rooms" :key="item.id">
                                        <div class="roomInfo">
                                            <span>@{{ item.name }}</span>
                                            <span>@{{ item.price }}/晚</span>
                                        </div>
                                        <img :src="item.cover" style="width:100%;margin-top:-25%">

                                    </el-carousel-item>
                                </el-carousel>


                            </div>
                            <div class="widget-user-image" style="z-index:99">
                                <img class="img-circle"
                                     src="{{$user->avatar?:icon($user->nickname)}}"
                                     alt="User Avatar">
                            </div>
                            <div class="box-footer">
                                <div class="row">
                                    <div class="col-sm-6 bb">
                                        <div class="description-block">
                                            <div class="description-text" style="margin-bottom:20px">房间</div>
                                            <span class="description-header">@foreach($room as $v) [{{$v->name}}
                                                ]@endforeach</span>
                                        </div>

                                    </div>
                                    <div class="col-sm-6 bb bl">
                                        <div class="description-block">
                                            <div class="description-text">状态</div>
                                            <span class="description-header">
                                                @if($model->status==0)
                                                    <el-tag>待支付</el-tag> @endif
                                                @if($model->status==1)
                                                    <el-tag type="success">已支付</el-tag>@endif
                                                @if($model->status==2)
                                                    <el-tag type="info">已入住</el-tag> @endif
                                                @if($model->status==10)
                                                    <el-tag type="success">已完成</el-tag> @endif
                                                @if($model->status==-10)
                                                    <el-tag type="danger">已取消</el-tag> @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 bb">
                                        <div class="description-block">
                                            <div class="description-text">入住时间</div>
                                            <span class="description-header">{{$model->checkin}}</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 bb bl">
                                        <div class="description-block">
                                            <div class="description-text">退房时间</div>
                                            <span class="description-header">{{$model->checkout}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3 bb">
                                        <div class="description-block">
                                            <div class="description-text">客户</div>
                                            <span class="description-header">{{$model->customer}}</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 bb bl">
                                        <div class="description-block">
                                            <div class="description-text">客户要求</div>
                                            <span class="description-header">{{$model->requirements?:'-'}}</span>

                                        </div>
                                    </div>
                                    <div class="col-sm-3 bb bl">
                                        <div class="description-block">
                                            <div class="description-text">手机</div>
                                            <span class="description-header">{{$model->mobile}}</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 bb bl">
                                        <div class="description-block">
                                            <div class="description-text">入住人数</div>
                                            <span class="description-header">{{$model->people}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 bb">
                                        <div class="description-block">
                                            <div class="description-text">价格</div>
                                            <span class="description-header" style="color:red">￥{{$model->price}}</span>
                                        </div>
                                        <!-- /.description-block -->
                                    </div>
                                    <div class="col-sm-6 bb bl">
                                        <div class="description-block">
                                            <div class="description-text">实付</div>
                                            <span class="description-header"
                                                  style="color:red">￥{{$model->real_price}}</span>
                                        </div>
                                        <!-- /.description-block -->
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 bb">
                                        <div class="description-block">
                                            <div class="description-text">创建时间</div>
                                            <span class="description-header">{{$model->created_at}}</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 bb bl">
                                        <div class="description-block">
                                            <div class="description-text">修改时间</div>
                                            <span class="description-header">{{$model->updated_at}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="col-sm-12 bb">
                                        <div class="description-block">
                                            <div class="description-text">备注
                                                <el-button type="primary" @click="editVisible=!editVisible" size="mini">
                                                    编辑
                                                </el-button>
                                            </div>
                                            <div v-if="!editVisible">

                                                <span class="description-header"
                                                      style="font-size:12px;">@{{ remark }}</span>
                                                <span class="description-header" style="font-size:12px;"
                                                      v-for="item in requirements">@{{item.requirements}}
                                            </span>
                                            </div>
                                            <div v-if="editVisible">

                                                <el-input type="textarea" v-model="form.myRequirements"></el-input>
                                                <el-button style="margin-top:10px;" @click="editRequirements()"
                                                           size='mini' type="success">保存备注
                                                </el-button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 bb">
                                        <div class="description-block">
                                            <div class="description-text">操作</div>
                                            <span class="description-header">

                                                <div v-if="status==0">
                                                    <el-button type="info" @click="changeStatusFunc(1)"
                                                               round>线下已支付</el-button>
                                                    <el-button type="danger" @click="changeStatusFunc(-10)"
                                                               round>取消</el-button>
                                                </div>
                                                <div v-if="status==1">
                                                    <el-button type="primay" @click="changeStatusFunc(2)"
                                                               round>客户已入住</el-button>
                                                    <el-button type="danger" @click="changeStatusFunc(-10)"
                                                               round>取消</el-button>
                                                </div>
                                                <div v-if="status==2">
                                                      <el-button type="success" @click="changeStatusFunc(10)"
                                                                 round>服务完成</el-button>
                                                </div>
                                                <div v-if="status==10">
                                                    -
                                                </div>
                                                <div v-if="status==-10  && isRefund==1">
                                                                      <el-button type="danger" @click="changeStatusFunc(-11)" round>完成退款</el-button>
                                                </div>

                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.row -->
                            </div>
                        </div>

                        <!-- /.widget-user -->
                    </div>


                </div>
            </div>
        </div>

        <el-dialog title="温馨提示" :visible.sync="dialogVisible">
            <el-form v-if="changeStatus==1 || changeStatus==2" :model="form">
                <el-form-item label="实付金额" label-width="120px">
                    <el-input v-model="form.real_price" auto-complete="off"></el-input>
                </el-form-item>
            </el-form>
            <div style="text-align: center" v-if="changeStatus==2">确定客户已经入住？</div>
            <div v-if="changeStatus==10">确定服务完成？</div>

            <div v-if="changeStatus==-10">确定取消订单吗?</div>
            <div v-if="changeStatus==-11">确定完成用户退款了吗?</div>

            <div slot="footer" class="dialog-footer">
                <el-button @click="dialogVisible = false">取 消</el-button>
                <el-button type="primary" @click="submit()">确 定</el-button>
            </div>
        </el-dialog>

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

        .widget-user-2 .widget-user-username {
            margin-top: 5px;
            margin-bottom: 5px;
            font-size: 14px;
            font-weight: 300;
        }

    </style>
@stop

@section('javascript')
    <script>
        let model ={!! $model !!},
            requirements = {!! $model->experience_booking_requirements !!}
                rooms = {!! $room !!}
                myRequirements = "{!! $myRequirements !!}"
        console.log(requirements);
        new Vue({
            el: '#app',
            data: {
                status: model.status,
                requirements: requirements,
                remark: model.remark,
                isRefund:model.is_refund,
                rooms: rooms,
                dialogVisible: false,
                changeStatus: '',
                editVisible: false,
                form: {
                    real_price: model.real_price,
                    myRequirements: myRequirements
                }

            },
            methods: {
                changeStatusFunc(status){
                    this.changeStatus = status;
                    this.dialogVisible = true;
                },
                editRequirements(){
                    if (!this.form.myRequirements) {
                        this.$message.error('不能保存空的备注');
                        return false;
                    }
                    let params = {requirements: this.form.myRequirements}
                    this.$http.post("{{route('experience_bookings.editRequirements',$model)}}", params).then(res => {
                        reload();
                    })
                },
                submit(){
                    let params = {status: this.changeStatus};
                    if (this.changeStatus == 1 || this.changeStatus == 10 || this.changeStatus == 2 ||this.changeStatus==-11) {
                        params.real_price = this.form.real_price
                    }
                    this.$http.post('{{route('experience_bookings.changeStatus',$model)}}', params).then(res => {
                         reload();
                    })
                },
            }
        })

        function reload() {
            swal({
                title: '操作成功',
                text: '1秒后关闭',
                timer: 1000
            }).then(
                function () {
                    window.location.reload();
                },
            ).catch(res => {
                window.location.reload();
            })
        }
    </script>
@stop
