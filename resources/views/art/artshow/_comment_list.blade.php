

    <!-- Box Comment -->
    @foreach($comments as $key=>$v)
    <div class="box box-widget">
        <div class="box-header with-border">
            <div class="user-block">
                <img class="img-circle" src="{{$v->owner->avatar}}" alt="User Image">
                <span class="username"><a href="#">{{$v->owner->nickname}}</a></span>
                <span class="description">评论于 - {{$v->created_at->diffForHumans()}}</span>
            </div>
            <!-- /.user-block -->
            <div class="box-tools">
                <button @click="del({{$v->id}})" type="button" class="btn btn-danger btn-xs">删除</button>
                @if(count($v->auth_user_liked))
                    <button type="button" class="btn btn-success btn-xs" @click="like({{$v->id}},'art_comment')">已点赞</button>
                    @else
                    <button type="button" class="btn btn-primary btn-xs" @click="like({{$v->id}},'art_comment')">点赞</button>
                    @endif

            </div>
            <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->
        <div class="box-body">

            <p>{{$v->comment}}</p>

            <span class="pull-right text-muted">{{$v->like_count}} 个赞 - {{$v->reply_count}} 个回复</span>
        </div>
        <!-- /.box-body -->
        @foreach($v->childs as $m=>$n)
        <div class="box-footer box-comments">
            <div class="box-comment">
                <!-- User image -->
                <img class="img-circle img-sm" src="{{$n->owner->avatar}}" alt="User Image">

                <div class="comment-text">
                      <section class="username">
                        {{$n->owner->nickname}}

                          <div class="pull-right text-muted" style="margin-left:10px;">
                              @if(count($n->likes)>0)

                                  <button @click="like({{$n->id}},'art_comment')" type="button" class="btn btn-success btn-xs">已点赞</button>
                              @else
                                  <button @click="like({{$n->id}},'art_comment')" type="button" class="btn btn-default btn-xs">点赞</button>
                                  @endif
                              <button @click="del({{$n->id}})" type="button" class="btn btn-default btn-xs">删除</button>
                          </div>

                        <span class="text-muted pull-right">
                           发表于: {{$n->created_at->diffForHumans()}}
                        </span>

                          @if($n->replies_to_user && $n->to_be_reply_id!=$n->parent_id)
                              @<a>{{$n->replies_to_user->owner['nickname']}}</a>
                          @endif
                      </section><!-- /.username -->
                   <p>
                       {{$n->comment}}
                   </p>
                    <span class="pull-right text-muted">{{$n->like_count}} 个赞 </span>

                    @include('art.artshow._comment_form',['comment'=>$n,'parent_id'=>$v->id,'art'=>$art,'to_be_reply_id'=>$n->id])

                </div>
                <!-- /.comment-text -->
            </div>
            <!-- /.box-comment -->
            <!-- /.box-comment -->
        </div>
        @endforeach
        <!-- /.box-footer -->
        <div class="box-footer">
            @include('art.artshow._comment_form',['comment'=>$v,'parent_id'=>$v->id,'art'=>$art,'to_be_reply_id'=>$v->id])
        </div>
        <!-- /.box-footer -->
    </div>
    @endforeach
    <!-- /.box -->
