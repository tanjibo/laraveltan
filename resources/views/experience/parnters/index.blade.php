@extends("layouts.main")

@section("content")
    <section class="content" id="app">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">合作伙伴</h3>
                <div class="pull-right">
                    @can('experience_partners_create')
                        <el-button @click="add()" round type="success">添加合作</el-button>
                    @endcan
                </div>
            </div>

            <div class="box-body">
                <el-table
                :data="model"
                border
                style="width: 100%">
                <el-table-column
                        prop="id"
                        label="ID"
                        width="180">
                </el-table-column>
                <el-table-column
                        prop="name"
                        label="合作方"
                        width="180">
                </el-table-column>
                <el-table-column
                        prop="token"
                        label="token">
                </el-table-column>
                    <el-table-column
                            prop="mini_url"
                            label="小程序入口地址">
                    </el-table-column>
                    <el-table-column
                            fixed="right"
                            label="操作"
                            width="100">
                        <template slot-scope="scope">
                            <el-button @click="del(scope.row.id)" type="text" size="small">删除</el-button>
                        </template>
                    </el-table-column>
                </el-table>
            </div>
        </div>
    </section>
    @endsection

@section("javascript")

    <script>
        let model={!! isset($model)?$model->toJson():'[]'!!};
        let vm= new Vue({
             el:'#app',
             data:{
                 model:model
             },
            methods:{
                 del(id){
                     let url = laroute.route('experience_partners.destroy', {experience_partner: id});
                     delModal(() => {
                         this.$http.post(url, {_method: "DELETE"}).then(res => {
                            window.location.reload();
                         })
                     })
                 },
                add(){
                     window.location.href="{{route('experience_partners.create')}}"
                }
            }
         })
    </script>

    @endsection

