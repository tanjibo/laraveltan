<?php
$action = request()->segment(1);
$method = request()->route()->getActionMethod();
$breadcrumb[ 0 ] = config('breadcrumb.' . $action . '/' . 'index');
if (count(request()->segments()) > 1)
    $breadcrumb[ 1 ] =config('breadcrumb.' . $action . '/' . $method);


?>

<section class="content-header" style="padding: 15px 15px 15px 15px;">
    <h1>
        {{end($breadcrumb)['name']}}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> 首页</a></li>
        @empty(!array_filter($breadcrumb))
        @foreach($breadcrumb as $v)
            @if($loop->index>0)
                <li class="active">{{$v['name']}}</li>
            @else
                <li><a href="{{route($v['url'])}}">{{$v['name']}}</a></li>
            @endif
        @endforeach
            @endempty
    </ol>
</section>