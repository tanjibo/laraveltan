<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 25/7/2018
 * Time: 5:21 PM
 */

namespace Repositories;


use App\Models\User;

class StoreUserRepository
{

    public function storeUserByMemberCard(){

    }
    public function storeUserByWebMobile( $wxUserInfo )
    {

        $userData = [
            'avatar'   => $wxUserInfo[ "headimgurl" ],
            'nickname' => $wxUserInfo[ "nickname" ],
            'gender'   => $wxUserInfo[ "sex" ],
            'open_id'  => $wxUserInfo[ "openid" ],
            'status'   => User::USER_STATUS_ON,
            'phone'    => $wxUserInfo[ 'phone' ],

        ];
        return $this->storeUser($userData, User::SOURCE_DRAW);


    }

    /**
     * 通过安吉小程序添加用户
     */
    public function storeUserByExperienceMini( array $userInfo )
    {

        $userData                   = $this->combineUserData($userInfo);
        $userData[ 'mini_open_id' ] = $userInfo[ 'openId' ];
        return $this->storeUser($userData, User::SOURCE_EXPERIENCEROOM);

    }

    /**
     * 通过安吉小程序添加用户
     */
    public function storeUserByTearoomMini( array $userInfo )
    {

        $userData                   = $this->combineUserData($userInfo);
        $userData[ 'tearoom_open_id' ] = $userInfo[ 'openId' ];

        return $this->storeUser($userData, User::SOURCE_TEAROOM);

    }


    /**
     * @param array $userInfo
     * 通过中式空间添加用户
     */
    public function storeUserByArtMini( array $userInfo )
    {

        $userData                  = $this->combineUserData($userInfo);
        $userData[ 'art_open_id' ] = $userInfo[ 'openId' ];
        return $this->storeUser($userData, User::SOURCE_ARTSHOW);

    }


    protected function combineUserData( array $userInfo )
    {
        extract($userInfo);
        $userData = [
            'avatar'   => $avatarUrl,
            'nickname' => $nickName,
            'union_id' => $unionId,
            'gender'   => $gender,
            'status'   => User::USER_STATUS_ON,
        ];
        return $userData;
    }


    /**
     * @param $data
     * @param $source
     * @return $this|\Illuminate\Database\Eloquent\Model|null|static
     * 存储用户
     */
    protected function storeUser( $data, $source )
    {

        if ($user = User::findUserByUnionId($data[ 'union_id' ])) {
            $user->update($data);
        }
        else {
            //用户来源
            $data[ 'source' ] = $source;
            return User::query()->create($data);
        }
        return $user;
    }

}