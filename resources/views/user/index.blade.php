@extends('layouts.main')

@section('content')

    <section class="content" id="app">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">用户列表</h3>
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

                            <div class="name">@{{ props.item.nickname }}</div>
                            <div>
                                <a v-if="props.item.avatar" style="width:50px;height:50px;box-sizing: content-box"
                                   class="thumbnail">
                                    <img class="img-responsive" :src="props.item.avatar"
                                         style="width:50px;height:50px;">
                                </a>
                                <a v-else>
                                    -
                                </a>
                            </div>


                        </template>
                    </el-autocomplete>
                </div>
            </div>

            <div class="box-body">
                <el-table
                        :data="tableData"
                @sort-change="sortChange"
                style="width: 100%">
                </el-table-column>
                <el-table-column prop="id" label="用户ID" sortable="custom">

                </el-table-column>

                <el-table-column prop="avatar" label="头像">
                    <template slot-scope="scope">
                        <a v-if="scope.row.avatar" style="width:50px;height:50px;box-sizing: content-box"
                           class="thumbnail">
                            <img class="img-responsive" :src="scope.row.avatar"
                                 style="width:50px;height:50px;" alt="">

                        </a>
                        <span v-else>
                            无
                        </span>

                    </template>
                </el-table-column>
                <el-table-column
                        prop="nickname"
                        label="昵称">

                </el-table-column>

                <el-table-column prop="mobile" label="手机号" sortable="custom">
                    <template slot-scope="scope">
                        <div v-if="scope.row.mobile">
                            @{{ scope.row.mobile }}
                        </div>
                        <div v-else>-</div>
                    </template>

                </el-table-column>
                <el-table-column prop="nickname" label="用户名称" sortable="custom">

                </el-table-column>
                <el-table-column prop="is_lrss_staff" label="内部员工" sortable="custom">
                    <template slot-scope="scope">
                        <div v-if="scope.row.is_lrss_staff">
                            是
                        </div>
                        <div v-else>否</div>
                    </template>
                </el-table-column>
                <el-table-column  label="来源">
                    <template slot-scope="scope">

                        <el-tag size="mini" v-if="scope.row.source==0">商城</el-tag>
                        <el-tag size="mini" type="primary" v-if="scope.row.source==11">旅游族</el-tag>
                        <el-tag size="mini" type="info" v-if="scope.row.source==1">安吉</el-tag>
                        <el-tag size="mini" type="success" v-if="scope.row.source==12">空间展示</el-tag>
                        <el-tag size="mini" type="danger" v-if="scope.row.source==2">茶舍</el-tag>
                    </template>
                </el-table-column>
                <el-table-column prop="created_at" label="注册时间" sortable="custom">

                </el-table-column>

                <el-table-column label="操作">
                    <template slot-scope="scope">
                        @can('users_update')
                            <a :href="'/user/'+scope.row.id+'/edit'" class="btn btn-sm btn-success">编辑</a>
                        @endcan

                        @hasanyrole('admin|superAdmin')
                            <a v-if="scope.row.is_lrss_staff" :href="'/user/give_permissions/'+scope.row.id"
                               class="btn btn-sm btn-success">权限</a>
                        @endhasanyrole

                        @can('users_del')
                            <el-button
                                    size="mini"
                                    type="danger"
                                    @click="handleDelete(scope.row.id)">删除
                            </el-button>
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
@section("javascript")



    <script type="text/javascript">


        new Vue({
            el: '#app',
            data: {
                filters: [
                    {text: '全部', value: ""},
                    {text: '商城', value: "0"},
                    {text: '旅游族', value: "11"},
                    {text: '安吉', value: "1"},
                    {text: '中式空间', value: "12"},
                    {text: '茶舍', value: "2"}
                ],
                tableData: [],
                multipleSelection: [],
                querys: {'columns': 'id', 'order': 'descending'},
                currentPage: 1,
                perPage: 10,
                total: 0,
                pageSizes: [10, 20, 30, 40],
                searchData: '',
                searchStatus: ''//检索订单状态


            },
            mounted(){
                let url = laroute.route('user.index_api');
                this.$http.post(url, this.querys).then(res => {
                    this.tableData = res.data;  //分页数据
                    this.perPage = res.per_page; //每页数量
                    this.total = res.total; //总数量
                })
            },
            methods: {

                status(val){
                    this.querys.select = val ? {"source": this.searchStatus} : "";
                    this.searchStatus = val;
                    let url = laroute.route('user.index_api', {page: this.currentPage});

                    this.$http.post(url, this.querys).then(res => {
                        this.tableData = res.data;
                        this.perPage = res.pre_page; //每页数量
                        this.total = res.total; //总数量
                    }).catch(res => {

                    })
                },

                sortChange(column){
                    let url = laroute.route('user.index_api', {page: this.currentPage});
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
                    let url = laroute.route('user.index_api', {page: this.currentPage});
                    this.$http.post(url, this.querys).then(res => {
                        this.tableData = res.data;
                        this.perpage = res.per_page;
                        this.total = res.total;
                    })
                },
                handleCurrentChange(val) {
                    console.log(val);
                    this.currentPage = val;
                    let url = laroute.route('user.index_api', {page: val});
                    this.$http.post(url, this.querys).then(res => {
                        this.tableData = res.data;
                        this.perPage = res.per_page;
                        this.total = res.total;
                    })
                },
                querySearchAsync(queryString, cb) {
                    let results = '';
                    clearTimeout(this.timeout2)
                    this.timeout2 = setTimeout(() => {
                        this.querys.search = queryString;
                        let url = laroute.route('user.index_api', {page: this.currentPage});
                        this.$http.post(url, this.querys).then(res => {
                            results = this.tableData = res.data;
                            this.perPage = res.per_page;
                            this.total = res.total;
                        });
                    }, 300)

                    clearTimeout(this.timeout);
                    this.timeout = setTimeout(() => {
                        cb(results);
                    }, 1000 * Math.random());
                },
                handleSelect(item) {

                    this.tableData = [item];
                    this.prePage = 0;
                    this.total = 0;
                },
                handleDelete(index){
                    let url = laroute.route('user.destroy', {user: index});
                    delModal(() => {
                        this.$http.post(url, {_method: 'DELETE'}).then(res => {
                            reload();
                        })
                    })

                }
            }

        })
    </script>

@endsection

