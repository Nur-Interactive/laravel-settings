<?php

namespace Nurinteractive\Settings;

class Facade extends \Illuminate\Support\Facades\Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'Nurinteractive\Settings\Setting\SettingStorage';
    }
}
