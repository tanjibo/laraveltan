@extends('layouts.main')

@section('content')
    <div class="content" id="app">
        <div class="box box-slider">
            <el-form ref="form" :model="form" label-width="80px">
                <div class="box-header">
                    <h4>添加评论</h4>
                </div>
                <div class="box-body">
                    <div class="col-md-8 col-md-offset-2">
                        <el-form-item label="内部用户">
                            <el-select v-model="form.user_id" placeholder="请选择内部用户">
                                <el-option v-for="item in userArr" :label="item.nickname" :value="item.id"></el-option>

                            </el-select>
                        </el-form-item>
                        <el-form-item label="展品">
                            <el-select  v-model="form.art_show_id" placeholder="请选择展品">
                                <el-option v-for="item in artArr" :label="item.name" :value="item.id"></el-option>
                            </el-select>
                        </el-form-item>

                        <el-form-item label="评论">
                            <el-input type="textarea" v-model="form.comment"></el-input>
                        </el-form-item>
                    </div>

                </div>
                <div class="box-footer col-md-12">
                    <el-form-item>
                        <el-button type="primary" @click="sub">立即创建</el-button>
                    </el-form-item>
                </div>
            </el-form>

        </div>
    </div>
@stop
@section('javascript')

    <script>
        let userArr={!! $user !!},artArr={!! $art !!};
        let vm=  new Vue({
            el:"#app",
            data:{
                userArr:userArr,
                artArr:artArr,
                form:{
                    comment:'',
                    user_id:'',
                    art_show_id:''
                }
            },
            methods:{
                sub(){
                  if(!(this.form.comment && this.form.user_id&& this.form.art_show_id)){
                      this.$message.error('请填写正确信息');
                      return false;
                  }
                    this.$http.post('{{route('art_comment.store')}}',this.form).then(res=>{
                        reload();
                    })
                }
            }
        })
    </script>
@stop