<?php

namespace App;

use App\Traits\UserSettingsTrait;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    use UserSettingsTrait;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];
    protected $appends = ['avatar', 'getroles'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];



    public function getAvatarAttribute()
    {
        $path = Storage::exists('/public/users/' . $this->id . '.jpg');
        $avatar = ($path) ? asset('storage/users/' . $this->id . '.jpg') : asset('storage/users/0.jpg');
        return $avatar;
    }

    public function getGetrolesAttribute()
    {
        return $this->roles;
    }

    public function getTokenAttribute()
    {

        return Str::random(60);
    }

    public function settings()
    {
        return $this->hasMany(Setting::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function bills()
    {
        return $this->hasMany(Bill::class);
    }

    public function balance(){
        $dept=0;
        $credit=0;
        foreach ($this->bills as $trans){
            if($trans->amount>0){
                $dept=$dept+$trans->amount;
            }else{
                $credit=$credit+$trans->amount;
            }
        }
        return -1*($dept+$credit);
    }


}
