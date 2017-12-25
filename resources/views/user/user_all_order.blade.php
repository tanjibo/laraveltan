@extends("layouts.main")

@section("content")
    <section class="content" id="app">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">个人全部订单</h3>

            </div>

            <div class="box-body">
                <el-table :data="tableData"
                style="width: 100%">
                <el-table-column label="ID" prop="id" sortable="custom">
                    <template slot-scope="scope">
                        <a v-if="scope.row.tearoom" :href="'/tearoom_booking/'+scope.row.id">@{{ scope.row.id }}</a>
                        <a v-else :href="'/experience_bookings/'+scope.row.id">@{{ scope.row.id }}</a>
                    </template>
                </el-table-column>
                <el-table-column label="房间/茶舍" prop="experience_booking_rooms">
                    <template slot-scope="scope">
                        <span v-for="item in scope.row.experience_booking_rooms">【@{{ item.name }}】</span>
                        <span v-if="scope.row.tearoom">【@{{ scope.row.tearoom.name }}】</span>
                    </template>
                </el-table-column>
                <el-table-column label="入住/茶舍时间" prop="checkin" style="width:150px">
                    <template slot-scope="scope">
                         @{{ scope.row.checkin }}
                        <div v-if="scope.row.tearoom">
                            <p>@{{ scope.row.date }}</p>
                            <p>@{{ scope.row.time }}</p>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column label="退房" prop="checkout" >

                </el-table-column>
                <el-table-column label="客户" prop="customer" >

                </el-table-column>
                <el-table-column label="人数" prop="people">
                    <template slot-scope="scope">
                        @{{ scope.row.people }}
                        <div v-if="scope.row.tearoom">
                            @{{ scope.row.peoples }}
                        </div>
                    </template>
                </el-table-column>
                <el-table-column label="价钱" prop="price" >
                    <template slot-scope="scope">
                        <span style="color:#FA5555;font-weight:700">￥@{{ scope.row.price }}</span>
                        <span style="color:#FA5555;font-weight:700">￥@{{ scope.row.fee }}</span>
                    </template>
                </el-table-column>
                <el-table-column label="实付" prop="real_price">
                    <template slot-scope="scope">
                        <span style="color:#FA5555;font-weight:700">￥@{{ scope.row.real_price }}</span>
                        <span style="color:#FA5555;font-weight:700">￥@{{ scope.row.real_fee }}</span>
                    </template>
                </el-table-column>
                <el-table-column label="订单来源" prop="source">
                </el-table-column>
                <el-table-column label="状态" prop="status">
                    <template slot-scope="scope">
                        <el-tag size="mini" v-if="scope.row.status==0">待支付</el-tag>
                        <el-tag size="mini" type="success" v-if="scope.row.status==1">已支付</el-tag>
                        <el-tag size="mini" type="info" v-if="scope.row.status==2">已入住</el-tag>
                        <el-tag size="mini" type="success" v-if="scope.row.status==10">已完成</el-tag>
                        <el-tag size="mini" type="danger" v-if="scope.row.status==-10">已取消</el-tag>
                    </template>
                </el-table-column>
                </el-table>


            </div>
        </div>


    </section>
@endsection

@section('javascript')
    <script>
        new Vue({
            el: '#app',
            data: {

                tableData: [],

            },
            mounted(){
                let url='{{route('user.user_all_order',$user)}}';
                this.$http.post(url,this.querys).then(res => {

                    this.tableData = res;  //分页数据

                })
            },
            methods: {




            }
        })
    </script>
@stop
