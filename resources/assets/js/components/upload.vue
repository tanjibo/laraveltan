<template>
    <div style="overflow: hidden">
        <el-upload
                action="/upload/qiniu"
                list-type="picture-card"
                :on-preview="handlePictureCardPreview"
                :file-list="imgUrl"
                :on-success=handleAvatarSuccess
                :before-upload="beforeAvatarUpload"
                :headers="{'X-CSRF-TOKEN':token}"
                :on-remove="handleRemove">
            <i class="el-icon-plus"></i>
        </el-upload>
        <el-dialog :visible.sync="dialogVisible">
            <img width="100%" :src="dialogImageUrl" alt="">
        </el-dialog>

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
                token:window.axios.defaults.headers.common['X-CSRF-TOKEN'],
                dialogImageUrl: '',
                dialogVisible: false,

            }
        },
        methods: {
            /**
             *  删除上传图片
             * @param file
             * @param fileList
             */
            handleRemove(file, fileList) {
                let index = '';
                if (this.imgUrl) {
                    this.imgUrl.forEach(function (item, key) {
                        if (file.url == item.url) index = key;
                    })
                }

                if (index > -1) {
                    this.imgUrl.splice(index, 1);
                }

            },
            /**
             * 显示图片
             * @param file
             */
            handlePictureCardPreview(file) {
               console.log(file.url);
                this.dialogImageUrl = file.url;
                this.dialogVisible = true;
            },

            handleAvatarSuccess(res, file) {
                this.imgUrl.push({url: res.url})
            },
            beforeAvatarUpload(file) {
                const isJPG = (file.type === 'image/jpeg' || file.type === 'image/png' || file.type == 'image/gif');
                const isLt2M = file.size / 1024 / 1024 < 2;
                const length = this.imgUrl.length;
                if (length > 5) {
                    this.$message.error('上传图片最多为5张');
                }
                if (!isJPG) {
                    this.$message.error('上传头像图片只能是 JPG 格式!');
                }
                if (!isLt2M) {
                    this.$message.error('上传头像图片大小不能超过 2MB!');
                }
                return isJPG && isLt2M;
            },

        }

    }


</script>