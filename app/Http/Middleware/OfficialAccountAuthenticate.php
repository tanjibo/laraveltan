<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 31/5/2018
 * Time: 8:37 PM
 */

namespace App\Http\Middleware;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Event;
use Overtrue\LaravelWeChat\Events\WeChatUserAuthorized;
use Overtrue\LaravelWeChat\Middleware\OAuthAuthenticate;

class OfficialAccountAuthenticate extends OAuthAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param string|null              $scopes
     *
     * @return mixed
     */
    public function handle($request, \Closure $next, $account = 'default', $scopes = null)
    {
        // $account 与 $scopes 写反的情况
        if (is_array($scopes) || (\is_string($account) && str_is('snsapi_*', $account))) {
            list($account, $scopes) = [$scopes, $account];
            $account || $account = 'default';
        }

        $isNewSession = false;
        $sessionKey = \sprintf('wechat.oauth_user.%s', $account);
        $config = config(\sprintf('wechat.official_account.%s', $account), []);
        $officialAccount = app(\sprintf('wechat.official_account.%s', $account));
        $scopes = $scopes ?: array_get($config, 'oauth.scopes', ['snsapi_base']);

        if (is_string($scopes)) {
            $scopes = array_map('trim', explode(',', $scopes));
        }

        $session = session($sessionKey, []);

        if (!$session) {
            if ($request->has('code')) {
                session([$sessionKey => $officialAccount->oauth->user() ?? []]);
                $isNewSession = true;

                Event::fire(new WeChatUserAuthorized(session($sessionKey), $isNewSession, $account));

                return redirect()->to($this->getTargetUrl($request));
            }

            session()->forget($sessionKey);




            //本地和测试环境下使用这个
            if(App::environment()=='local' ||App::environment()=="test"){
                return $officialAccount->oauth->scopes($scopes)->redirect($request->fullUrl());
            }


            $query = $request->getQueryString();

            $question = $request->getBaseUrl().$request->getPathInfo() == '/' ? '/?' : '?';

            $url= $query ? $request->getPathInfo().$question.$query : $request->getPathInfo();

            $url="http://m.liaorusanshe.com".$url;
            return $officialAccount->oauth->scopes($scopes)->redirect($url);
        }

        Event::fire(new WeChatUserAuthorized(session($sessionKey), $isNewSession, $account));

        return $next($request);
    }

    /**
     * Build the target business url.
     *
     * @param Request $request
     *
     * @return string
     */
    protected function getTargetUrl($request)
    {
        $queries = array_except($request->query(), ['code', 'state']);

        return $request->url().(empty($queries) ? '' : '?'.http_build_query($queries));
    }
}