@extends('layouts.main')

@section('content')
    <div class="content" id="app">
        <div class="box box-slider">
            <div class="row">
                <div class="col-md-12">

                    <div class="box box-widget">

                        <div class="box-body">
                            <div style="text-align: center"><img style="display: inline-block" class="img-responsive pad" src="{{$art->cover}}?imageView2/2/w/300/h/300" alt="Photo"></div>

                            <p>{{$art->attr}}</p>
                            <section
                                    style="text-align: center;border:1px solid #dedede;border-radius: 3px;box-shadow: 3px 3px 5px #ddd;overflow: hidden;height:auto;padding:10px;">
                                <span>{!!$art->introduction !!}</span>
                                <span class="pull-right text-muted">  {{$art->comment_count}} 个评论</span>
                            </section>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer box-comments">
                            @if($art->comment_count>0)
                                @foreach($comments as  $key=>$v)
                                    <div class="box-comment"
                                         style="border:1px solid #dedede;margin-bottom:5px;padding:10px;background:white;">
                                        <img class="img-circle img-sm" src="{{$v->owner->avatar}}" alt="User Image">

                                        <div class="comment-text">
                                            <div class="username">
                                                <span>{{$v->owner->nickname}}</span>
                                                <span class="text-muted pull-right">{{$v->created_at}}</span>
                                                @if(auth()->id()!=$v->owner->id)


                                                    @if($v->isLikeBy(auth()->id()))
                                                        <button @click="like({{$v->id}})" type="button"
                                                                class="btn btn-success btn-xs"><i
                                                                    class="fa fa-thumbs-o-up"></i> 已点赞
                                                        </button>
                                                    @else
                                                        <button @click="like({{$v->id}})" type="button"
                                                                class="btn btn-default btn-xs"><i
                                                                    class="fa fa-thumbs-o-up"></i> 点个赞吧
                                                        </button>
                                                    @endif

                                                @endif
                                                <div class="btn btn-xs btn-danger" @click="del({{$v->id}})">删除评论</div>
                                                <span class="pull-right text-muted"
                                                      style="margin-right:7px;"> {{$v->like_count}}个赞</span>
                                            </div><!-- /.username -->
                                            <p>
                                                @if($v->parent_id)
                                                    @<span>{{$v->replies->owner->nickname}}
                                                        :</span>
                                                @endif
                                                <span>{!! $v->comment !!}</span>
                                            </p>
                                        </div>
                                        <div>
                                            @includeWhen(auth()->id()!=$v->owner->id,'art.artshow._comment_form',['art'=>$art,'parent_id'=>$v->id])
                                        </div>

                                        <!-- /.comment-text -->
                                    </div>

                                @endforeach
                            @else
                                <div class="box-comment">
                                    <p style="text-align: center">暂无评论</p>
                                </div>
                            @endif

                        </div>
                        <!-- /.box-footer -->
                        <div class="box-footer">
                            @include('art.artshow._comment_form',['art'=>$art])
                        </div>
                        <!-- /.box-footer -->
                    </div>
                    <!-- /.box -->
                </div>

            </div>

        </div>
    </div>

@stop
@section('javascript')
    <script>
        let vm = new Vue({
            el: '#app',
            data: {},
            methods: {
                like(id){
                    console.log(id);
                    this.$http.post('{{route("art_comment_like.store")}}', {art_show_comment_id: id}).then(res => {
                        reload();
                    })
                },
                del(id){
                    let form = {};
                    form._method = "DELETE";
                    form.art_show_comment_id = id;
                    let url = laroute.route('art_comment.destroy', {art_comment: id});
                    this.$http.post(url, form).then(res => {

                    })
                }
            }
        })
    </script>
@stop