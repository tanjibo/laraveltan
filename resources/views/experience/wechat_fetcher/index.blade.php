@extends("layouts.main")
@section('content')
    <section class="content">
        <a href="{{route("experience_wechat_fetcher.create")}}" class="btn btn-success pull-right" >添加微信爬虫文章</a>
        <div style="margin-top:40px;" class="row">
            @foreach($model as $v)
                <div class="col-md-4">

                    <!-- Profile Image -->
                    <div class="box box-primary">
                        <div class="box-body box-profile">
                            <img class="profile-user-img img-responsive" src="{{$v->cover_img}}"
                                 alt="User profile picture">

                            <h4 class="profile-username text-center" style="font-size:16px">{{str_limit($v->title, 20, '...')}}</h4>

                            <p class="text-muted text-center">{{$v->desc}}</p>

                            <a href="{{route('experience_wechat_fetcher.edit',['experience_wechat_fetcher'=>$v->id])}}"
                               class="btn btn-primary"><b>选择文章类型</b></a>
                            <div class="pull-right" style="margin-top: 5px;">时间:{{$v->created_at->toDateString()}}</div>

                        </div>
                    </div>


                </div>
            @endforeach


        </div>


    </section>
@endsection
