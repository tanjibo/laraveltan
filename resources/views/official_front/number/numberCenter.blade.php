@extends("official_front.main.layout")
@section("title","{$activity->name}-抽奖码")
@section("content")
<section id="app">
    <section class="info">
        <section class="code">
            <p class="xt-1">您的抽奖码<span>共{{count($model->draw_number)}}张</span></p>
            <ul>
                @foreach($model->draw_number as $v)
                    <li>
                        <p>您抽到的抽奖码</p>
                        <p>{{$v->created_at}}</p>
                        <p class="c-bg">{{$v->draw_number}}</p>
                    </li>
                @endforeach
            </ul>
            <p class="xt-2">抽奖码满12个请添加客服微信：<span>13820135018</span></p>
        </section>
        <section class="bt-2">
            <a href="#" class="fl" @click="shareShow()">邀请好友拿抽奖券</a>
            <a href="{{route("officialAccount.user.poster",['official_activity_id'=>$activity])}}" class="fr">生成海报拿抽奖券</a>
        </section>
        <section class="explain">
            <a href="{{route('officialAccount.user.numberList',['official_activity_id'=>$activity])}}">抽奖码排行榜</a>
            <p>
                温馨提示：中奖码=INT(xxx,xxx/1,000,000)*抽奖码个数
            </p>
            <p>
                注：INT(x)为取整函数，取开奖当天的上证指数倒序6位。例：公平起见，取开奖当天收盘的上证指数倒序6位，例如指数为2702.03，则倒序为302072，抽奖人数为1000人，中奖码为302.07，即第302位为中奖者。
            </p>
        </section>
    </section>
    <section class="share-friend" v-show="share" style="display:none">
        <section class="share-arrow"></section>
        <section class="xb">
            <p class="close" @click="shareShow()"></p>
            <p class="tx"><img src="{{$model->avatar}}"></p>
            <p class="zt">点击右上角，选择 <span>【发送给朋友】</span> 邀请好友。好友点击文章内活动报名二维码报名成功，您即可额外获得1个抽奖码，数量无上限哦！</p>
        </section>
    </section>
</section>

@endsection
@section("js")
    <script type="text/javascript">
        let vm = new Vue({
            el: '#app',
            data(){
                return {
                    share: false
                }
            },
            methods: {
                shareShow: function () {
                  this.share=!this.share
                }
            }
        })


        {!!$shareFriendJs!!}
    </script>
@endsection