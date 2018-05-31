@extends("official_front.main.layout")
@section("title","了如三舍")
@section("content")
    <section id="app">

        <section class="press"  v-cloak v-if="share">
            <p>长按保存下方专属海报</p>
            <p>好友识别参与活动，您将额外获得抽奖码，数量无上限哦！</p>
            <img :src="imgUrl" alt="">
        </section>

        <section class="press"  v-cloak v-if="!share">
           @if(request()->status=='be_end') <p>活动已结束,请关注下一期吧</p>@endif
           @if(request()->status=='not_start') <p>活动还未开始,客官可不要着急呦</p>@endif
           @if(request()->status=='not_found') <p>活动不存在或已经结束了</p>@endif
            <p>长按下方二维码，关注<span style="font-weight: 700;color:red;">了如三舍</span>公众号，更多免费活动等着你！</p>
            <img style="margin-top:80px;" src="{{asset('/officialAccount/img/ewm.jpg')}}" alt="">
            <p style="font-size:40px;">了如三舍</p>
        </section>

    </section>

@endsection
@section("js")
    <script type="text/javascript">
        let vm = new Vue({
            el: '#app',
            data(){
                return {
                    share: false,
                    imgUrl:'',
                }
            },
            mounted:function(){

            }

        })


    </script>
@endsection
