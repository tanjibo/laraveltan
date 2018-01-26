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
                                         src="{{$booking->user->avatar?:icon($booking->user->nickname)}}"
                                         alt="User Avatar">
                                </div>
                                <!-- /.widget-user-image -->
                                <div class="widget-user-username">{{$booking->user->nickname}}</div>
                                <h5 class="widget-user-desc">电话:{{$booking->user->mobile}}</h5>
                            </div>
                            <div class="box-footer no-padding">
                                <ul class="nav nav-stacked">
                                    <li><a href="{{route('user.user_all_order',$booking->user)}}">总共订单数 <span
                                                    class="pull-right">{{$booking->user->totalOrderNum()}}</span></a>
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
                                    <el-carousel-item>

                                        <img src="{{$booking->tearoom->cover}}" style="width:100%;margin-top:-25%">

                                    </el-carousel-item>
                                </el-carousel>


                            </div>
                            <div class="widget-user-image" style="z-index:99">
                                <img class="img-circle"
                                     src="{{$booking->user->avatar?:icon($booking->user->nickname)}}"
                                     alt="User Avatar">
                            </div>
                            <div class="box-footer">
                                <div class="row">
                                    <div class="col-sm-6 bb">
                                        <div class="description-block">
                                            <div class="description-text" style="margin-bottom:20px">空间</div>
                                            <span class="description-header">[{{$booking->tearoom->name}}]</span>
                                        </div>

                                    </div>
                                    <div class="col-sm-6 bb bl">
                                        <div class="description-block">
                                            <div class="description-text">状态</div>
                                            <span class="description-header">
                                                @if($booking->status==0)
                                                    <el-tag>待支付</el-tag> @endif
                                                @if($booking->status==1)
                                                    <el-tag type="success">已支付</el-tag>@endif
                                                @if($booking->status==2)
                                                    <el-tag type="info">已入住</el-tag> @endif
                                                @if($booking->status==10)
                                                    <el-tag type="success">已完成</el-tag> @endif
                                                @if($booking->status==-10)
                                                    <el-tag type="danger">已取消</el-tag> @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 bb">
                                        <div class="description-block">
                                            <div class="description-text">价钱</div>
                                            <span class="description-header"
                                                  style="color:red;">￥{{$booking->fee}}</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 bb bl">
                                        <div class="description-block">
                                            <div class="description-text">实付</div>
                                            <span class="description-header"
                                                  style="color:red;">￥{{$booking->real_fee}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3 bb">
                                        <div class="description-block">
                                            <div class="description-text">客户</div>
                                            <span class="description-header">{{$booking->customer}}</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 bb bl">
                                        <div class="description-block">
                                            <div class="description-text">客户要求</div>
                                            <span class="description-header"
                                                  style="font-size:12px;">{{$booking->tips?:'-'}}</span>

                                        </div>
                                    </div>
                                    <div class="col-sm-3 bb bl">
                                        <div class="description-block">
                                            <div class="description-text">手机</div>
                                            <span class="description-header">{{$booking->mobile}}</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 bb bl">
                                        <div class="description-block">
                                            <div class="description-text">预约人数</div>
                                            <span class="description-header">{{$booking->peoples}}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6 bb">
                                        <div class="description-block">
                                            <div class="description-text">创建时间</div>
                                            <span class="description-header">{{$booking->created_at}}</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 bb bl">
                                        <div class="description-block">
                                            <div class="description-text">修改时间</div>
                                            <span class="description-header">{{$booking->updated_at}}</span>
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
                                                    @can('tearoom_booking_del')
                                                        <el-button type="danger" @click="changeStatusFunc(-10)"
                                                                   round>取消</el-button>
                                                    @endcan
                                                </div>
                                                @can('tearoom_booking_del')
                                                    <div v-if="status==1">
                                                    <el-button type="danger" @click="changeStatusFunc(-10)"
                                                               round>取消</el-button>
                                                </div>
                                                @endcan
                                                <div v-if="status==2">
                                                      <el-button type="success" @click="changeStatusFunc(10)"
                                                                 round>服务完成</el-button>
                                                </div>
                                                <div v-if="status==10">
                                                    -
                                                </div>
                                                <div v-if="status==-10">
                                                    -
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
            <el-form v-if="changeStatus==1" :model="form">
                <el-form-item label="实付金额">
                    <el-input class="sm-" v-model="form.real_fee" auto-complete="off"></el-input>
                </el-form-item>
            </el-form>
            <div v-if="changeStatus==10">确定服务完成？</div>
            <div v-if="changeStatus==-10">确定取消订单吗?</div>

            <div slot="footer" class="dialog-footer">
                {{--<el-button  @click="dialogVisible = false">取 消</el-button>--}}
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
        let model ={!! $booking !!},
            requirements = {!! $booking->tearoom_booking_requirements !!}
                myRequirements = "{!! $myRequirements !!}"
        console.log(requirements);
        new Vue({
            el: '#app',
            data: {
                status: model.status,
                requirements: requirements,
                remark: model.remark,
                dialogVisible: false,
                changeStatus: '',
                editVisible: false,
                form: {
                    real_fee: model.real_fee,
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
                    this.$http.post("{{route('tearoom_booking.editRequirements',$booking)}}", params).then(res => {
                        reload();
                    })
                },

                submit(){
                    let params = {status: this.changeStatus};
                    if (this.changeStatus == 1 || this.changeStatus == 10) {
                        params.real_fee = this.form.real_fee
                    }
                    if(!params.real_fee){
                        this.$message.error('请输入实付金额');
                        return false;
                    }
                    
                    this.$http.post('{{route('tearoom_booking.changeStatus',$booking)}}', params).then(res => {
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
