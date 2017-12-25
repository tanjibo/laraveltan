<template>
    <div class="col-md-offset-3 col-md-6">
        <!-- Box Comment -->
        <div style="border:1px solid #dedede;" class="box box-widget">
            <div class="box-header with-border">
                <div class="user-block">
                    <img class="img-circle" :src="commentData.owner.avatar" alt="User Image">
                    <span class="username"><a href="#">{{commentData.owner.nickname}}</a></span>
                    <span class="description">评论于 {{commentData.created_at}}</span>
                </div>

                <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <img class="img-responsive pad" :src="commentData.art_show.cover+'?imageView2/1/w/100/format/webp/q/70/interlace/1|imageslim'" alt="Photo">
                <p>{{commentData.art_show.attr}}</p>
                <span class="pull-right text-muted">{{commentData.art_show.comment_count}} 评论数</span>
            </div>
            <!-- /.box-body -->
            <div class="box-footer box-comments">
                <div class="box-comment">
                    <!-- User image -->
                    <img class="img-circle img-sm" :src=" commentData.owner.avatar" alt="User Image">

                    <div class="comment-text">
                      <span class="username">
                       {{commentData.owner.nickname}}
                        <span class="text-muted pull-right">{{commentData.created_at}}</span>
                      </span><!-- /.username -->
                      {{commentData.comment}}
                    </div>
                    <!-- /.comment-text -->
                </div>

            </div>

        </div>
        <!-- /.box -->
    </div>
</template>

<script>
    export default{
        props:['art'],
        data(){
            return {
                commentData:this.art
            }
        }
    }
</script>