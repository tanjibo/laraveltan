<template>
    <el-form inline class="demo-table-expand">
        <div class="box box-widget">
            <div class="box-header with-border">
                <div class="user-block">
                    <img class="img-circle" :src="comment.user.avatar" alt="User Image">
                    <span class="username"><a>{{comment.user.nickname }}</a></span>
                    <span class="description">{{comment.created_at}}</span>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div>{{ comment.content }}</div>
                <!-- Attachment -->
                <div v-if="comment.experience_comment_pics.length" class="attachment-block clearfix">
                    <a :href="item.pic_url" data-toggle="lightbox"
                       style="display:inline-block;width:100px;height:100px;margin-left:10px" class="thumbnail"
                       v-for="item in comment.experience_comment_pics">
                        <img :src="item.pic_url+'?imageView2/1/w/100/format/webp/q/70/interlace/1|imageslim'">
                    </a>
                </div>
                <!-- /.attachment-block -->
            </div>
            <!-- /.box-body -->
            <div v-if="comment.is_reply" class="box-footer box-comments">
                <div class="box-comment">
                    <!-- User image -->
                    <img class="img-circle img-sm" :src="admin.avatar"
                         alt="User Image">

                    <div class="comment-text">
                      <span class="username">
                        {{ comment.reply.username }}
                        <span class="text-muted pull-right">{{comment.reply.create_at}}</span>
                      </span><!-- /.username -->
                        @{{ comment.reply.reply }}
                    </div>
                    <!-- /.comment-text -->
                </div>

            </div>
            <!-- /.box-footer -->
            <div v-else class="box-footer">
                <form action="#" method="post">
                    <img class="img-responsive img-circle img-sm"
                         :src="admin.avatar" alt="Alt Text">
                    <!-- .img-push is used to add margin to elements next to floating images -->
                    <div class="img-push">
                        <el-input type="input" v-model="replyData" placeholder="请回复。。。"></el-input>
                        <el-button type="success" size="mini" @click="reply" style="margin-top:10px;">回复</el-button>
                    </div>
                </form>
            </div>
            <!-- /.box-footer -->
        </div>
    </el-form>
</template>
<script>
    export default{
        props: ['comment', 'admin'],
        data(){
            return {
                replyData: '',
                commentData: this.comment,
                token: window.axios.defaults.headers.common['X-CSRF-TOKEN'],
            }
        },
        methods: {
            reply(){
                if (!this.replyData) {
                    this.$message.error('回复内容不能为空');
                    return false;
                }
                let url = laroute.route('experience_comments.reply', {experience_comment: this.comment.id})
                this.$http.post(url, {
                    reply: this.replyData,
                    admin_id: this.admin.id,
                    username: this.admin.nickname,
                    comment_id:this.comment.id
                }).then(res => {
                    reload()
                })
            }
        }
    }
</script>