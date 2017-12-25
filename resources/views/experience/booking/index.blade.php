@extends("layouts.main")

@section("content")
    <section class="content" id="app">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">订单列表</h3>
                <div class="pull-right">
                    <el-select v-model="searchStatus" @change="status" placeholder="检索订单状态">
                        <el-option
                                v-for="item in filters"
                                :key="item.value"
                                :label="item.text"
                                :value="item.value">
                        </el-option>
                    </el-select>
                    <el-autocomplete
                            v-model="searchData"
                            :fetch-suggestions="querySearchAsync"
                            placeholder="请输入内容"
                            @select="handleSelect"
                    >
                        <template slot-scope="props">
                            <el-popover trigger="hover" placement="top">
                                <p>入住: @{{ props.item.checkin }}</p>
                                <p>退房: @{{ props.item.checkout }}</p>
                                <p>客户: @{{ props.item.customer }}</p>
                                <div slot="reference" class="name-wrapper">
                                    <el-tag size="medium">@{{ props.item.id }}</el-tag>
                                </div>
                            </el-popover>
                            {{--<div class="name">@{{ props.item.id }}</div>--}}
                        </template>
                    </el-autocomplete>
                    @can('experience_booking_create')
                        <el-button @click="goBooking()" round type="success">在线预约</el-button>
                    @endcan
                </div>
            </div>

            <div class="box-body">
                <el-table
                        :data="tableData"
                @sort-change="sortChange"
                style="width: 100%">
                <el-table-column label="ID" prop="id" sortable="custom">
                    <template slot-scope="scope">
                        <a :href="'/experience_bookings/'+scope.row.id">@{{ scope.row.id }}</a>
                    </template>
                </el-table-column>
                <el-table-column label="房间" prop="experience_booking_rooms">
                    <template slot-scope="scope">
                        <span v-for="item in scope.row.experience_booking_rooms">【@{{ item.name }}】</span>
                    </template>
                </el-table-column>
                <el-table-column label="入住" prop="checkin" sortable="custom">

                </el-table-column>
                <el-table-column label="退房" prop="checkout" sortable="custom">

                </el-table-column>
                <el-table-column label="客户" prop="customer" sortable="custom">

                </el-table-column>
                <el-table-column label="人数" prop="people" sortable="custom">

                </el-table-column>
                <el-table-column label="价钱" prop="price" sortable="custom">
                    <template slot-scope="scope">
                        <span style="color:#FA5555;font-weight:700">￥@{{ scope.row.price }}</span>
                    </template>
                </el-table-column>
                <el-table-column label="实付" prop="real_price" sortable="custom">
                    <template slot-scope="scope">
                        <span style="color:#FA5555;font-weight:700">￥@{{ scope.row.real_price }}</span>
                    </template>
                </el-table-column>
                <el-table-column label="订单来源" prop="source" sortable="custom">
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
                <el-table-column label="操作" fixed="right" width="120">
                    <template slot-scope="scope">
                        @can('experience_booking_update')
                            <el-button type="text" size="small" @click="editBooking(scope.row.id)">编辑</el-button>
                        @endcan
                        @can('experience_booking_del')
                            <el-button type="text" size="small" @click="delBooking(scope.row.id)">删除</el-button>
                        @endcan
                    </template>
                </el-table-column>
                </el-table>

                <div class="pull-right">
                    <el-pagination
                    @size-change="handleSizeChange"
                    @current-change="handleCurrentChange"
                    :current-page.sync="currentPage"
                    :page-size="perPage"
                    layout="total, sizes, prev, pager, next, jumper"
                    :page-sizes="pageSizes"
                    :total="total">
                    </el-pagination>
                </div>
            </div>
        </div>


    </section>
@endsection

@section('javascript')
    <script>
        new Vue({
            el: '#app',
            data: {
                filters: [
                    {text: '全部', value: ""},
                    {text: '待支付', value: "0"},
                    {text: '已支付', value: "1"},
                    {text: '已入住', value: "2"},
                    {text: '已完成', value: "10"},
                    {text: '已取消', value: "-10"}
                ],
                multipleSelection: [],
                querys: {'columns': 'id', 'order': 'descending'},
                currentPage: 1,
                perPage: 10,
                total: 100,
                pageSizes: [10, 20, 30, 40],
                searchData: '',
                tableData: [],
                searchStatus: ''//检索订单状态
            },
            mounted(){
                let url = laroute.route('experience_bookings.index_api');
                this.$http.post(url, this.querys).then(res => {
                    this.tableData = res.data;  //分页数据
                    this.perPage = res.per_page; //每页数量
                    this.total = res.total; //总数量
                })
            },
            methods: {
                editBooking(id){
                    window.location.href = laroute.route('experience_bookings.edit', {experience_booking: id});

                },
                delBooking(id){
                    let url = laroute.route('experience_bookings.destroy', {experience_booking: id});
                    console.log(url);
                    delModal(() => {
                        this.$http.post(url, {_method: "DELETE"}).then(res => {
                            window.location.reload();
                        })
                    })
                },

                status(val){

                    this.querys.select = val ? {"status": this.searchStatus} : "";
                    this.searchStatus = val;
                    let url = laroute.route('experience_bookings.index_api', {page: this.currentPage});
                    this.$http.post(url, this.querys).then(res => {
                        this.tableData = res.data;
                        this.perPage = res.pre_page; //每页数量
                        this.total = res.total; //总数量
                    }).catch(res => {

                    })
                },
                sortChange(column){
                    console.log(column);
                    let url = laroute.route('experience_bookings.index_api', {page: this.currentPage});
                    this.$http.post(url, {
                        'columns': column.prop,
                        'order': column.order
                    }).then(res => {
                        this.tableData = res.data;
                        this.perPage = res.pre_page; //每页数量
                        this.total = res.total; //总数量
                    }).catch(res => {

                    })
                },
                handleSizeChange(val) {

                    this.querys.prePage = val;
                    this.perPage = val;
                    let url = laroute.route('experience_bookings.index_api', {page: this.currentPage});
                    this.$http.post(url, this.querys).then(res => {
                        this.tableData = res.data;
                        this.perPage = res.per_page;
                        this.total = res.total;
                    })
                },
                handleCurrentChange(val) {
                    console.log(val);
                    this.currentPage = val;
                    let url = laroute.route('experience_bookings.index_api', {page: val});
                    this.$http.post(url, this.querys).then(res => {
                        this.tableData = res.data;
                        this.perPage = res.per_page;
                        this.total = res.total;
                    })
                },
                querySearchAsync(queryString, cb) {
                    // this.searchData=this.tableData;
                    let results = '';
                    clearTimeout(this.timeout2)
                    this.timeout2 = setTimeout(() => {

                        this.querys.search = queryString;
                        let url = laroute.route('experience_bookings.index_api', {page: this.currentPage});
                        this.$http.post(url, this.querys).then(res => {
                            results = this.tableData = res.data;
                            this.perPage = res.per_page;
                            this.total = res.total;
                        });
                    }, 300)

                    clearTimeout(this.timeout);
                    this.timeout = setTimeout(() => {
                        cb(results);
                    }, 1500 * Math.random());
                },
                handleSelect(item) {
                    console.log(item)
                    this.tableData = [item];
                    this.perPage = 0;
                    this.total = 0;
                },
                goBooking(){
                    window.location.href = "{{route('experience_rooms.index',['booking'=>true])}}"
                }
            }
        })
    </script>
@stop
