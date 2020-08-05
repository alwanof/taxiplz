<?php

namespace App\Traits;

use App\Configuration;
use App\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

trait UserSettingsTrait
{

    private function getCacheKey($key)
    {
        $key = strtoupper($key);
        return "users.$key";
    }

    // get setting value
    public function getSetting($name)
    {


        $cacheKey = $this->getCacheKey('.all' . $name);
        return cache()->remember($cacheKey, Carbon::now()->addMinutes(5), function () use ($name) {
            $role=$this->roles()->first();
            if(!$role){
                abort(403);
            }

            $config=Configuration::where('name',$name)->first();
            $rolehasConfig=$config->roles->where('name',$role->name)->count();
            if($rolehasConfig==0){
                return Configuration::where('name',$name)->first();
            }

            $setting_attempt= Setting::where(['name' => $name, 'user_id' => $this->id]);
            if($setting_attempt->count()>0){
                return $setting_attempt->first();
            }else{
                return Configuration::where('name',$name)->first();
            }
        });



    }
}
