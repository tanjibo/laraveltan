@extends("official_front.main.layout")
@section("title","{$activity->name}-排行榜")
@section("content")
    <section id="app">
        <section class="p-list">
            <section class="tit"></section>
            <ul>
                @foreach($ranking as $k=>$v)
                    <li>
                        <p class="fl"><span>{{$k+1}}</span></p>
                        <p class="fl"><img src="{{$v->user->avatar}}"></p>
                        <p class="fl">{{$v->user->nickname}}</p>
                        <p class="fr">邀请码{{$v->draw_number_count}}个</p>
                    </li>
                @endforeach
            </ul>
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
                    this.share = !this.share
                }
            }
        })


    </script>
@endsection