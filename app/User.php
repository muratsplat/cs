<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Auth\Passwords\CanResetPassword;

use App\Contracts\Auth\ClearSettleAuthenticatable;
use App\Contracts\Auth\ClearSettleEloquentPayload;
use Illuminate\Foundation\Auth\Access\Authorizable;
use App\Contracts\Auth\ClearSettleEloquentPayloadAble;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
//use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use App\Contracts\Auth\ClearSettleEloquentAuthenticatable;


class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    ClearSettleAuthenticatable,
                                    ClearSettleEloquentPayload
                                    //CanResetPasswordContract
{
    use Authenticatable,
        Authorizable, 
        ClearSettleEloquentAuthenticatable,
        ClearSettleEloquentPayloadAble; //, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];
}
