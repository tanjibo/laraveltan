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
                         <el-form-item label="房间">
                             <el-select  v-model="form.commentable_id" placeholder="请选择活动区域">
                                 <el-option v-for="item in roomArr" :label="item.name" :value="item.id"></el-option>
                             </el-select>
                         </el-form-item>
                         <el-form-item label="评分">
                             <el-rate
                                     v-model="form.score"
                                     :colors="['#99A9BF', '#F7BA2A', '#FF9900']">
                             </el-rate>
                         </el-form-item>

                         <el-form-item label="上传图片">
                             <uploader :imgurl.sync="form.imgUrl"></uploader>
                         </el-form-item>
                         <el-form-item label="评论">
                             <el-input type="textarea" v-model="form.content"></el-input>
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
        let userArr={!! $user !!},roomArr={!! $room !!};
      let vm=  new Vue({
            el:"#app",
            data:{
                userArr:userArr,
                roomArr:roomArr,
                form:{
                   content:'',
                    user_id:'',
                    commentable_id:'',
                    imgUrl:[],
                    score:0,
                }
            },
            methods:{
                sub(){

                  this.$http.post('{{route('experience_comments.store')}}',this.form).then(res=>{
                      reload();
                  })
                }
            }
        })
    </script>
@stop