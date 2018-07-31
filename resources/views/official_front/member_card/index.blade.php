@extends("official_front.main.layout")

@section("content")
11111
@endsection
@section('js')
    <script type="text/javascript">
        let data={!! $json !!};
        let config={!!  $config!!}
        wx.ready(function () {
            wx.addCard({
                cardList: {!! $json !!} , // 需要打开的卡券列表
                success: function (res) {
                    var cardList = res.cardList; // 添加的卡券列表信息
                    alert(cardList);
                }
            });
            {{--wx.chooseCard({--}}
                {{--shopId: '', // 门店Id--}}
                {{--cardType: '', // 卡券类型--}}
                {{--cardId: '{{$cardId}}', // 卡券Id--}}
                {{--timestamp: config.timestamp, // 卡券签名时间戳--}}
                {{--nonceStr: config.nonce_str, // 卡券签名随机串--}}
                {{--signType: 'SHA1', // 签名方式，默认'SHA1'--}}
                {{--cardSign: config.signature, // 卡券签名--}}
                {{--success: function (res) {--}}
                    {{--var cardList= res.cardList; // 用户选中的卡券列表信息--}}
                {{--}--}}
            {{--});--}}

            {{--wx.openCard({--}}
                {{--cardList: [{--}}
                    {{--cardId: '{{$cardId}}',--}}
                    {{--code: '{{$code}}'--}}
                {{--}]// 需要打开的卡券列表--}}
            {{--});--}}


        })



    </script>
@endsection