@extends("layouts.main")

@section("content")
    <div class="content">
        <div class="row">

            <!-- /.col -->
            @foreach($model as $v)
                <div class="col-md-4">
                    <!-- Widget: user widget style 1 -->
                    <div class="box box-widget widget-user">
                        <!-- Add the bg color to the header using any of the bg-* classes -->
                        <div class="widget-user-header bg-black"
                             style="background: url('{{$v->cover}}') center center;">
                            <h3 class="widget-user-username">{{$v->name}}</h3>
                            <h5 class="widget-user-desc">{{$v->price}}/晚</h5>
                        </div>
                        <div class="widget-user-image">
                            <img class="img-circle" style="height:90px;width:90px;"
                                 src="{{$v->cover}}?imageView2/1/w/90/h/90"
                                 alt="User Avatar">
                        </div>

                        <div class="box-footer">
                            <div class="row">


                                <div class="col-sm-4 border-right">
                                    <div class="description-block">
                                        <h5 class="description-header">{{$v->created_at->diffForHumans()}}</h5>
                                        <span class="description-text">创建时间</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-6 border-right">

                                    <div class="description-block">
                                        @if(!request()->booking)
                                            @can('experience_room_update')
                                                <a href="{{route('experience_rooms.edit',$v)}}"
                                                   class="btn  btn-sm btn-info fa fa-edit"></a>
                                            @endcan
                                            @can('experience_room_date_lock')
                                                <a class="btn btn-sm btn-success fa fa-lock"
                                                   href="{{route('experience_rooms.lockDate',$v)}}"></a>
                                            @endcan
                                        @else
                                            @can('experience_booking_create')
                                                <a href="{{route('experience_bookings.create',['experience_room'=>$v])}}"
                                                   class="btn  btn-sm btn-info">在线预约</a>
                                            @endcan

                                        @endif
                                    </div>
                                    <!-- /.description-block -->
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
    </div>

@endsection