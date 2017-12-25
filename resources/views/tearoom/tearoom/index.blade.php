@extends("layouts.main")

@section("content")
    <div class="content">
        <div class="box box-info">
            <section class="box-header">
                @can('tearoom_create')
                    <a href="{{route('tearoom.create')}}" class="btn btn-info btn-sm pull-right">
                        添加茶舍空间
                    </a>
                @endcan

            </section>
            <section class="box-body">
                <div class="row">

                    <!-- /.col -->
                    @foreach($model as $v)
                        <div class="col-md-4">
                            <!-- Widget: user widget style 1 -->
                            <div style="border:1px solid #dedede;" class="box box-widget widget-user">
                                <!-- Add the bg color to the header using any of the bg-* classes -->
                                <div class="widget-user-header bg-black"
                                     style="background: url('{{$v->image}}') center center;">
                                    <h3 class="widget-user-username">{{$v->name}}</h3>
                                    <h5 class="widget-user-desc">1~{{$v->limits}}人</h5>
                                </div>
                                <div class="widget-user-image">
                                    <img class="img-circle" style="height:90px;width:90px;"
                                         src="{{$v->image}}?imageView2/1/w/90/h/90"
                                         alt="User Avatar">
                                </div>

                                <div class="box-footer">
                                    <div class="row">


                                        <div class="col-sm-3 border-right">
                                            <div class="description-block">
                                                <h5 class="description-header">{{$v->created_at->diffForHumans()}}</h5>
                                                <span class="description-text">创建时间</span>
                                            </div>
                                            <!-- /.description-block -->
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-sm-6 border-right">

                                            <div class="description-block">
                                                @if(request()->input('booking'))
                                                    @can('tearoom_booking_create')
                                                        <a href="{{route('tearoom_booking.create',['tearoom'=>$v])}}"
                                                           class="btn btn-success btn-xs">在线预约</a>
                                                    @endcan
                                                @else
                                                    @can('tearoom_update')
                                                        <a href="{{route('tearoom.edit',$v)}}"
                                                           class="btn  btn-sm btn-info fa fa-edit"></a>
                                                    @endcan
                                                    @can('tearoom_del')
                                                        <form style="display:inline-block"
                                                              action="{{route('tearoom.destroy',$v)}}"
                                                              method="POST">
                                                            {{csrf_field()}}
                                                            <input type="hidden" name="_method" value="DELETE">
                                                            <button type='submit'
                                                                    class="btn  btn-sm btn-danger fa fa-trash"></button>
                                                        </form>
                                                    @endcan
                                                    @can('tearoom_date_lock')
                                                        <a class="btn btn-sm btn-success fa fa-lock"
                                                           href="{{route('tearoom.lockDate',$v)}}"></a>
                                                    @endcan
                                                @endif
                                            </div>
                                            <!-- /.description-block -->
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="description-block">
                                                @if($v->status)
                                                    <p class="description-text label bg-blue">使用中</p>
                                                @else
                                                    <p class="description-text label bg-danger">禁用</p>
                                                @endif
                                            </div>
                                        </div>

                                    </div>
                                    <!-- /.row -->
                                </div>
                            </div>
                            <!-- /.widget-user -->
                        </div>

                @endforeach
                <!-- /.col -->
                </div>
            </section>

        </div>

    </div>

@endsection