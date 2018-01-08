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
                            <section
                                    style="text-align: center;border:1px solid #dedede;border-radius: 3px;box-shadow: 3px 3px 5px #ddd;overflow: hidden;height:auto;padding:10px;margin-top:10px;">
                               @if($isAuthUserLike)
                                <button @click="like({{$art->id}},'art_show')" class="btn btn-success">已点赞</button>
                                @else
                                <button @click="like({{$art->id}},'art_show')" class="btn btn-default">点个赞啊</button>
                                @endif
                                @if($isAuthUserCollect)
                                       <button @click="collect({{$art->id}})" class="btn btn-success">{{$art->collection_count}}人已收藏</button>

                                   @else
                                       <button @click="collect({{$art->id}})" class="btn btn-default">{{$art->collection_count}}人收藏</button>

                                   @endif
                                <div style="margin-top:10px;">
                                    @foreach($userLike as $v)
                                    <img  class="img-circle" style="width:50px;height:50px;" src="{{$v}}" alt="">
                                        @endforeach
                                </div>
                            </section>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer box-comments">

                            @if($art->comment_count>0)
                                @include('art.artshow._comment_list',['comments'=>$comments,'art'=>$art])
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
        function show(obj){
           $(obj).siblings('div').show();
           $(obj).hide();
        }
        let vm = new Vue({
            el: '#app',
            data: {},
            methods: {
                like(id,type){
                     let params={};
                    if(type=='art_show'){
                        params={id: id,type:'art_show'}
                    }else{
                        params={id: id,type:'art_show_comment'}
                    }
                    this.$http.post('{{route("art_comment_like.store")}}', params).then(res => {
                        reload();
                    })
                },
                collect(id){
                    let url=laroute.route('art_show_collect.store',{art_show:id});


                    this.$http.post(url).then(res => {
                        reload();
                    })
                },
                del(id){
                    let form = {};
                    form._method = "DELETE";
                    form.art_show_comment_id = id;
                    let url = laroute.route('art_comment.destroy', {art_comment: id});

                    delModal(()=>{this.$http.post(url, form).then(res => {
                     reload();
                    })})

                },

            }
        })
    </script>
@stop