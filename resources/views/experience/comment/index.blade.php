@extends('layouts.main')
@section('content')
    <section class="content" id="app">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">订单列表</h3>
                <div class="pull-right">
                    <el-button @click="goComment()" round size="mini" type="success">添加评论</el-button>
                </div>
            </div>

            <div class="box-body">
                <el-table
                        :data="tableData"
                @sort-change="sortChange"
                style="width: 100%">
                <el-table-column type="expand">
                    <template slot-scope="scope">
                        <comment :comment="scope.row" :admin="{{Auth()->user()}}"></comment>
                    </template>
                </el-table-column>
                <el-table-column label="ID" prop="id" sortable="custom">
                    <template slot-scope="scope">
                        <a :href="'/experience_bookings/'+scope.row.id">@{{ scope.row.id }}</a>
                    </template>
                </el-table-column>


                <el-table-column label="房间" prop="commentable_id" sortable="custom">
                    <template slot-scope="scope">
                        @{{ scope.row.experience_rooms.name}}
                    </template>
                </el-table-column>
                <el-table-column label="时间" prop="created_at" sortable="custom">

                </el-table-column>
                <el-table-column label="状态" prop="status" sortable="custom">
                    <template slot-scope="scope">
                        <el-tag v-if="scope.row.status" type="success" size="mini">正常</el-tag>
                        <el-tag v-else type="danger">锁定</el-tag>
                        <el-tag v-if="!scope.row.is_reply"  type="success" size="mini">可回复</el-tag>
                    </template>
                </el-table-column>

                <el-table-column label="操作"  width="120">
                    <template slot-scope="scope">
                        <el-button type="text" size="small" @click="delComment(scope.row.id)">删除</el-button>

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

@stop

@section('javascript')
    <link href="https://cdn.bootcss.com/ekko-lightbox/5.2.0/ekko-lightbox.min.css" rel="stylesheet">
    <script src="https://cdn.bootcss.com/ekko-lightbox/5.2.0/ekko-lightbox.min.js"></script>

    <script>

        $(function () {
            $(document).on('click', '[data-toggle="lightbox"]', function (event) {
                event.preventDefault();
                $(this).ekkoLightbox();
            });

        });
        let vm = new Vue({
            el: '#app',
            data: {
                form:{
                    reply:'',
                },
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
                let url=laroute.route('experience_comments.index_api',{page:this.currentPage})
                this.$http.post(url, this.querys).then(res => {
                    this.tableData = res.data;  //分页数据
                    this.perPage = res.per_page; //每页数量
                    this.total = res.total; //总数量
                })
            },
            methods: {

                delComment(id){
                    let url = laroute.route('experience_comments.destroy', {experience_comment: id});

                    delModal(() => {
                        this.$http.post(url, {_method: "DELETE"}).then(res => {
                            window.location.reload();
                        })
                    })
                },

                status(val){

                    this.querys.select = val ? {"status": this.searchStatus} : "";
                    this.searchStatus = val;
                    let url=laroute.route('experience_comments.index_api',{page:this.currentPage})
                    this.$http.post(url, this.querys).then(res => {
                        this.tableData = res.data;
                        this.perPage = res.pre_page; //每页数量
                        this.total = res.total; //总数量
                    }).catch(res => {

                    })
                },
                sortChange(column){

                    let url=laroute.route('experience_comments.index_api',{page:this.currentPage})
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
                    let url=laroute.route('experience_comments.index_api',{page:this.currentPage})
                    this.$http.post(url, this.querys).then(res => {
                        this.tableData = res.data;
                        this.perPage = res.per_page;
                        this.total = res.total;
                    })
                },
                handleCurrentChange(val) {
                    console.log(val);
                    this.currentPage = val;
                    let url=laroute.route('experience_comments.index_api',{page:val})
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
                        let url=laroute.route('experience_comments.index_api',{page:this.currentPage})
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
                goComment(){
                    window.location.href = "{{route('experience_comments.create')}}"
                }
            }
        })
    </script>
@stop