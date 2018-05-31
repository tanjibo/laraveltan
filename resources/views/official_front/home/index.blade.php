@extends("official_front.main.layout")
@section("title",$activity->name)
@section("content")
    @if(isset($drawUser) && !$drawUser->draw_number_count)

    <section class="info">
        <section class="registration">
            <p>您已成功提交报名信息</p>
            <section class="share-friend"></section>
            <section class="share-arrow"></section>
        </section>
    </section>
    @else
        <section id="app">
            <section class="info" v-if="!share ||!sub">
                <section class="words"></section>
                <section class="phone">
                    <input type="tel" placeholder="填写手机号码" v-model="phone" class="inp">
                    <p>以上信息用于通知中奖信息,请仔细核对</p>
                    <input type="submit" value="下一步" class="sub" @click="goNext()">
                </section>
            </section>

            <section class="info" v-show="share"  v-cloak>
                <section class="registration">
                    <p>您已成功提交报名信息</p>
                    <section class="share-friend"></section>
                    <section class="share-arrow"></section>
                </section>
            </section>

            <section class="info" v-show="sub"  v-cloak>
                <section class="follow">
                    <section class="closew"></section>
                    <section class="sz">长按下方二维码，关注【了如三舍】，点击菜单【免费住】完成报名。</section>
                    <section class="ewm"><img src="{{asset('officialAccount/img/ewm.jpg')}}"></section>
                </section>
            </section>
        </section>
    @endif
@endsection
@section('js')
    <script type="text/javascript">
        let official_activity_id={{$activity->id}};
        let vm = new Vue({
            el: '#app',
            data(){
                return {
                    share:false,
                    sub: false,
                    phone: "18610729170",
                }
            },

            methods: {
                goNext: function () {
                    let reg = 11 && /^((13|14|15|17|18)[0-9]{1}\d{8})$/;
                    if (!this.phone || !reg.test(this.phone)) {
                        swal('Oops...', '请仔细核对电话号码!', 'error');
                        return false;
                    }
                    this.$http.post("{{route('officialAccount.getPhone')}}", {phone: this.phone,'official_activity_id':official_activity_id}).then(res => {
                        if (res.code == 'unsubscribe') {
                            this.sub = !this.sub;
                        } else {
                            this.share = !this.share;
                        }
                    }).catch(res => {

                    });
                }
            }
        })

        {!! $shareTimeLine !!}

    </script>
@endsection