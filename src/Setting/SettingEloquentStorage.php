<?php

namespace Nurinteractive\Settings\Setting;

use Illuminate\Database\Eloquent\Builder;

class SettingEloquentStorage implements SettingStorage
{
    /**
     * Group name.
     *
     * @var string
     *
     */
    protected $settingsGroupName = 'default';

    /**
     * Cache key.
     *
     * @var string
     */
    // protected $settingsCacheKey = 'app_settings';  //cache commented out for now


    /**
     * {@inheritdoc}
     */
    public function all($fresh = false)
    {
        // if ($fresh) { //cache commented out for now
        return $this->modelQuery()->pluck('val', 'name', 'secret');
        // }

        //cache commented out for now
        // return Cache::rememberForever($this->getSettingsCacheKey(), function () {
        //     return $this->modelQuery()->pluck('val', 'name', 'secret');
        // });
    }

    /**
     * {@inheritdoc}
     */
    public function get($key, $default = null, $fresh = false)
    {
        $value = $this->all($fresh)->get($key, $default);
        $secret = $this->all($fresh)->get('secret', false);
        if ($secret) {
            $value = decrypt($value);
        }
        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $val = null, $secret = false)
    {
        // if its an array, batch save settings
        if (is_array($key)) {
            foreach ($key as $name => $value) {
                $this->set($name, $value);
            }

            return true;
        }

        $setting = $this->getSettingModel()->firstOrNew([
            'name' => $key,
            'group' => $this->settingsGroupName,
        ]);

        $setting->val = $val;
        $setting->group = $this->settingsGroupName;
        $setting->secret = $secret;
        $setting->save();

        // $this->flushCache();//cache commented out for now

        return $val;
    }

    /**
     * {@inheritdoc}
     */
    public function has($key)
    {
        return $this->all()->has($key);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($key)
    {
        $deleted = $this->getSettingModel()->where('name', $key)->delete();

        // $this->flushCache(); //cache commented out for now

        return $deleted;
    }

    /**
     * {@inheritdoc}
     */
    // public function flushCache()
    // {
    //     return Cache::forget($this->getSettingsCacheKey()); //cache commented out for now
    // }

    /**
     * Get settings cache key.
     *
     * @return string
     */
    // protected function getSettingsCacheKey() //cache commented out for now
    // {
    //     return $this->settingsCacheKey . '.' . $this->settingsGroupName;
    // }

    /**
     * Get settings eloquent model.
     *
     * @return Builder
     */
    protected function getSettingModel()
    {
        $model = config('settings.model');
        return app($model);
    }

    /**
     * Get the model query builder.
     *
     * @return Builder
     */
    protected function modelQuery()
    {
        return $this->getSettingModel()->group($this->settingsGroupName);
    }

    /**
     * Set the group name for settings.
     *
     * @param  string  $groupName
     * @return $this
     */
    public function group($groupName)
    {
        $this->settingsGroupName = $groupName;

        return $this;
    }
}
