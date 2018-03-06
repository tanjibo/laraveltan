@extends("layouts.main")
@section('content')
    <section class="content">
        <a href="{{route("experience_more_article.create")}}" class="btn btn-success pull-right" style="margin-bottom: 20px;">添加文章</a>
        <div class="row">
            @foreach($model as $v)
                <div class="col-md-4">

                    <!-- Profile Image -->
                    <div class="box box-primary">
                        <div class="box-body box-profile">
                            <img class="profile-user-img img-responsive" src="{{$v->cover_img}}"
                                 alt="User profile picture">

                            <h4 class="profile-username text-center" style="font-size:18px">{{$v->title}}</h4>

                            <p class="text-muted text-center">{{$v->desc}}</p>
                            <ul class="list-group list-group-unbordered">
                                @foreach($v->articleChild as $c)
                                    <li class="list-group-item" style="padding:15px 0;">
                                        <p>{{$c->title}}</p>
                                        <img class="profile-user-img img-responsive pull-right" src="{{$c->cover_img}}"
                                             style="height:50px;width:50px;margin-top: -40px;">
                                    </li>
                                @endforeach
                            </ul>
                            <a href="{{route('experience_more_article.edit',['experience_more_article'=>$v->id])}}"
                               class="btn btn-primary"><b>修改</b></a>
                            <div class="pull-right" style="margin-top: 5px;">时间:{{$v->created_at->toDateString()}}</div>

                        </div>
                    </div>


                </div>
            @endforeach


        </div>


    </section>
@endsection
