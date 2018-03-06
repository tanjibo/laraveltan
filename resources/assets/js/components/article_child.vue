<template>
    <div class="panel box box-primary">
        <div class="box-header with-border">
            <h4 class="box-title">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" :href="'#collapse'+num">
                    文章信息
                </a>
            </h4>
            <el-button  type="danger"  @click="del()" v-if="num!=0" size="mini" class="pull-right">删除子文章</el-button>

        </div>
        <div :id="'collapse'+num" class="panel-collapse collapse in">
            <div class="box-body">

                <el-form-item label="文章标题">
                    <el-input v-model="child.title"></el-input>
                </el-form-item>

                <el-form-item label="作者">
                    <el-input type="text" v-model="child.author"></el-input>
                </el-form-item>

                <el-form-item label="简介">
                    <el-input v-model="child.desc" placeholder="可以为空"></el-input>
                </el-form-item>
                <el-form-item label="文章类型">
                    <el-radio v-if="child.type=='1'" v-model="child.type" label="1">主文章</el-radio>
                    <el-radio v-else-if="child.type=='2'" v-model="child.type" label="2">子文章</el-radio>
                    <el-radio v-else v-model="child.type" label="0">三舍文章</el-radio>
                </el-form-item>

                <el-form-item label="正文内容">
                    <new-quill-editor :id="num" :editdata.sync="child.content"></new-quill-editor>
                </el-form-item>

                <el-form-item label="封面图片">
                    <uploader-single :imgurl.sync="child.cover_img"></uploader-single>
                </el-form-item>

            </div>
        </div>
    </div>
</template>

<script>

    export default {
        props:["child",'num'],
        data(){
            return{
            }
        },
        mounted() {

        },
        methods:{
            del(){
                console.log(this.num)

                this.$emit("delchild",this.num)
            }
        }
    }
</script>
