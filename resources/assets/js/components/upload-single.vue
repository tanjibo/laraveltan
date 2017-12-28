<template>
    <div>
        <el-upload
                v-loading="loading"
                class="avatar-uploader"
                element-loading-text="拼命上传中。。。"
                list-type="picture-card"
                action="/upload/qiniu"
                :show-file-list="false"
                :headers="{'X-CSRF-TOKEN':token}"
                :on-success="handleAvatarSuccess"
                :before-upload="beforeAvatarUpload"
        >

            <img v-if="imgUrl" :src="imgUrl" class="avatar">
            <i class="el-icon-plus avatar-uploader-icon" v-else></i>

        </el-upload>
    </div>

</template>
<style>
    .avatar-uploader {
        display: inline-block;
    }

    .avatar-uploader .el-upload {
        border: 1px dashed #d9d9d9;
        border-radius: 6px;
        cursor: pointer;
        position: relative;
        overflow: hidden;

    }

    .avatar-uploader .el-upload:hover {
        border-color: #20a0ff;
    }

    .avatar-uploader-icon {
        font-size: 28px;
        color: #8c939d;
        width: 150px;
        height: 150px;
        line-height: 150px;
        text-align: center;
    }

    input[type=file] {
        display: none;
    }

    .el-upload > .el-icon-plus {
        line-height: 150px;
    }

    .avatar {
        width: 150px;
        height: 150px;
        display: block;
    }

    .el-date-editor {
        display: inline-block;
    }

</style>
<script>

    export default {
        props: ['imgurl'],
        data(){
            return {
                imgUrl: this.imgurl,
                loading: false,
                token:window.axios.defaults.headers.common['X-CSRF-TOKEN']
            }
        },

        methods: {

            handleAvatarSuccess(res) {
                this.loading=false;
                this.imgUrl = res.url;
                //这一步必须
                this.$emit('update:imgurl', this.imgUrl);


            },

            beforeAvatarUpload(file) {
                const isJPG = (file.type === 'image/jpeg' || file.type === 'image/png' || file.type == 'image/gif');
                const isLt2M = file.size / 1024 / 1024 < 2;


                if (!isJPG) {
                    this.$message.error('上传头像图片只能是 JPG 格式!');
                }
                if (!isLt2M) {
                    this.$message.error('上传头像图片大小不能超过 2MB!');
                }
                this.loading = true;
                return isJPG && isLt2M;
            },
        }

    }


</script>