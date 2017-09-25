<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;


use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Nicolaslopezj\Searchable\SearchableTrait;

/**
 * Class Admin
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $last_login_ip
 * @property int $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 *
 * @package App\Models
 */
class Admin extends Authenticatable
{

    use HasApiTokens, Notifiable;
    use \Illuminate\Database\Eloquent\SoftDeletes;

    use SearchableTrait;

    protected $table = 'admin';

    protected $casts
        = [
            'status' => 'int',
        ];

    protected $hidden
        = [
            'password',
        ];

    protected $fillable
        = [
            'username',
            'password',
            'last_login_ip',
            'status',
        ];

    //搜索
    protected $searchable
        = [
            'columns' => [
                'user.username' => 10,
            ],
        ];

    public function findForPassport( $username )
    {

        return $this->where('username', $username)->first();

    }

    public function getAuthPassword()
    {
        return $this->attributes[ 'password' ];
    }
}
