<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Providers\User\EloquentUserAdapter;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends EloquentUserAdapter implements
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authorizable;
    use CanResetPassword;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function toDos(){
        return $this->user->hasMany('App\ToDo');
    }
}
