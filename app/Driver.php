<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Driver extends Model
{
    protected $fillable = [
        'name', 'phone', 'email', 'taxiCode', 'taxi', 'taxiNo', 'taxiColor', 'user_id', 'pn', 'pass'
    ];
    protected $appends = ['avatar', 'firm'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getAvatarAttribute()
    {
        $path = Storage::exists('/public/drivers/' . $this->id . '.jpg');
        $avatar = ($path) ? asset('storage/drivers/' . $this->id . '.jpg') : asset('storage/drivers/0.jpg');
        return $avatar;
    }
    public function getFirmAttribute()
    {
        return User::find($this->user_id)->email;
    }
}