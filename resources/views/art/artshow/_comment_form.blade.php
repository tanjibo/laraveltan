<form action="{{route('art_comment.store')}}" method="post">
    {!! csrf_field() !!}
    <input type="hidden" name="art_show_id" value="{{$art->id}}">
    <input type="hidden" name="user_id" value="{{auth()->id()}}">
    <img class="img-responsive img-circle img-sm" src="{{auth()->user()->avatar}}"
         alt="Alt Text">
    <!-- .img-push is used to add margin to elements next to floating images -->
    <div class="img-push">

        @if(!isset($parent_id))
            <input type="text" name="comment" class="form-control input-sm"
                   placeholder="写出你的评论，好不好啊">
            <input style="margin-top:20px;" class="btn btn-primary pull-right" type="submit"
                   value="提交评论">
        @else
            <input type="text" name="comment" class="form-control input-sm"
                   placeholder="好好回复">
            <input style="margin-top:20px;" class="btn btn-primary btn-sm pull-right" type="submit"
                   value="回复">
            <input type="hidden" name="parent_id" value="{{$parent_id}}">
        @endif

    </div>
</form>