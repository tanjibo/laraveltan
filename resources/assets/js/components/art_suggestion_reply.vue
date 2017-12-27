<template>
    <el-form inline class="demo-table-expand">
        <div class="box box-widget">
            <div class="box-header with-border">
                <div class="user-block">
                    <img class="img-circle" :src="suggestionData.user.avatar" alt="User Image">
                    <span class="username"><a>{{suggestionData.user.nickname }}</a></span>
                    <span class="description">{{suggestionData.created_at}}</span>
                </div>
            </div>

            <div class="box-body">
                <div>{{ suggestionData.content }}</div>
            </div>
            <!-- /.box-body -->
            <div v-if="suggestionData.reply" class="box-footer box-comments">
                <div class="box-comment">

                    <div class="comment-text">
                        <span class="text-muted">回复内容:{{suggestionData.reply}}</span>

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
        props: ['suggestion', 'admin'],
        data(){
            return {
                replyData: '',
                suggestionData: this.suggestion,
                token: window.axios.defaults.headers.common['X-CSRF-TOKEN'],
            }
        },
        methods: {
            reply(){
                if (!this.replyData) {
                    this.$message.error('回复内容不能为空');
                    return false;
                }
                let url = laroute.route('art_suggestion.reply', {art_suggestion: this.suggestion.id})
                this.$http.post(url, {
                    reply: this.replyData
                }).then(res => {
                    reload()
                })
            }
        }
    }
</script>