@extends("official_front.main.layout")
@section("title","{$activity->name}-海报")
@section("content")
    <section id="app">

        <section class="press"  v-cloak v-if="share">
            <p>长按保存下方专属海报</p>
            <p>好友识别参与活动，您将额外获得抽奖码，数量无上限哦！</p>
            <img :src="imgUrl" alt="">
        </section>

        <section class="press"  v-cloak v-if="!share">
            <p>正在努力生成海报中....</p>
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
                this.$http.post("{{route('officialAccount.user.poster',['official_activity_id'=>$activity])}}").then(res=>{
                    this.imgUrl=res.url;
                    this.share=!this.share;
                })
            }

        })


    </script>
@endsection