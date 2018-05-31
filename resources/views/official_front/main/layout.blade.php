<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=750,target-densitydpi=device-dpi , user-scalable=no"/>
    <title>@yield("title")</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="/officialAccount/css/style.css">
    <link rel="stylesheet" href="/adminLTEcomponents/sweetalert/sweetalert2.min.css">
    @yield('css')
</head>
<body>
@yield('content')
<script src="https://cdn.bootcss.com/vue/2.5.17-beta.0/vue.min.js"></script>
<script src="https://cdn.bootcss.com/jquery/3.3.0/jquery.min.js"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js" type="text/javascript" charset="utf-8"></script>
<script src="https://cdn.bootcss.com/axios/0.18.0/axios.min.js"></script>
<script src="/adminLTEcomponents/sweetalert/sweetalert2.min.js"></script>
{{--<script src="https://unpkg.com/promise-polyfill"></script>--}}
<script>
    //微信配置

    wx.config(<?php echo app("wechat.official_account")->jssdk->buildConfig(['onMenuShareTimeline','onMenuShareAppMessage','showMenuItems','hideAllNonBaseMenuItem'], false);?>);

    window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

    let token = document.head.querySelector('meta[name="csrf-token"]');
    if (token) {
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
    } else {
        console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
    }


    Vue.prototype.$http = axios.create({});

    Vue.prototype.$http.interceptors.response.use(function (response) {
        // Do something with response data
        return response.data;
    }, function (error) {
        // Do something with response erro
        let data = error.response.data;
        switch (error.response.status) {
            case 500:
                swal("发生错误了", data.message);
                break;
            case 502:
                swal("发生错误了", data.message);
                break;
            case 404:
                break;
        }

        return Promise.reject(error.response.data);

    });

    window.mobileUtil = (function (win, doc) {
        var UA = navigator.userAgent,
            isAndroid = /android|adr/gi.test(UA),
            isIos = /iphone|ipod|ipad/gi.test(UA) && !isAndroid,
            isMobile = isAndroid || isIos;
        return {
            isAndroid: isAndroid,
            isIos: isIos,
            isMobile: isMobile,

            isNewsApp: /NewsApp\/[\d\.]+/gi.test(UA),
            isWeixin: /MicroMessenger/gi.test(UA),
            isQQ: /QQ\/\d/gi.test(UA),
            isYixin: /YiXin/gi.test(UA),
            isWeibo: /Weibo/gi.test(UA),
            isTXWeibo: /T(?:X|encent)MicroBlog/gi.test(UA),
            tapEvent: isMobile ? 'tap' : 'click',
            /**
             * 缩放页面
             */
            fixScreen: function () {
                var metaEl = doc.querySelector('meta[name="viewport"]'),
                    metaCtt = metaEl ? metaEl.content : '',
                    matchScale = metaCtt.match(/initial\-scale=([\d\.]+)/),
                    matchWidth = metaCtt.match(/width=([^,\s]+)/);
                // console.log(metaEl)
                if (!metaEl) { // REM
                    var docEl = doc.documentElement,
                        maxwidth = docEl.dataset.mw || 750, // 每 dpr 最大页面宽度
                        dpr = isIos ? Math.min(win.devicePixelRatio, 3) : 1,
                        scale = 1 / dpr,
                        tid;
                    docEl.removeAttribute('data-mw');
                    docEl.dataset.dpr = dpr;
                    metaEl = doc.createElement('meta');
                    metaEl.name = 'viewport';
                    metaEl.content = fillScale(scale);
                    docEl.firstElementChild.appendChild(metaEl);

                    var refreshRem = function () {
                        var width = docEl.getBoundingClientRect().width;
                        if (width / dpr > maxwidth) {
                            width = maxwidth * dpr;
                        }
                        var rem = width / 16;
                        docEl.style.fontSize = rem + 'px';
                    };
                    win.addEventListener('resize', function () {
                        clearTimeout(tid);
                        tid = setTimeout(refreshRem, 300);
                    }, false);
                    win.addEventListener('pageshow', function (e) {
                        if (e.persisted) {
                            clearTimeout(tid);
                            tid = setTimeout(refreshRem, 300);
                        }
                    }, false);

                    refreshRem();
                } else if (isMobile && !matchScale && ( matchWidth && matchWidth[1] != 'device-width' )) { // 定宽
                    var width = parseInt(matchWidth[1]),
                        iw = win.innerWidth || width,
                        ow = win.outerWidth || iw,
                        sw = win.screen.width || iw,
                        saw = win.screen.availWidth || iw,
                        ih = win.innerHeight || width,
                        oh = win.outerHeight || ih,
                        ish = win.screen.height || ih,
                        sah = win.screen.availHeight || ih,
                        w = Math.min(iw, ow, sw, saw, ih, oh, ish, sah),
                        scale = w / width;

                    if (scale < 1) {
                        metaEl.content = metaCtt + ',' + fillScale(scale);
                    }
                }
                function fillScale(scale) {
                    return 'initial-scale=' + scale + ',maximum-scale=' + scale + ',minimum-scale=' + scale;
                }
            },

            /**
             * 转href参数成键值对
             * @param href {string} 指定的href，默认为当前页href
             * @returns {object} 键值对
             */
            getSearch: function (href) {
                href = href || win.location.search;
                var data = {}, reg = new RegExp("([^?=&]+)(=([^&]*))?", "g");
                href && href.replace(reg, function ($0, $1, $2, $3) {
                    data[$1] = $3;
                });
                return data;
            }
        };
    })(window, document);
    mobileUtil.fixScreen();



</script>

@yield('js')
</body>
</html>