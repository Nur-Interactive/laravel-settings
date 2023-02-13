<?php

namespace Nurinteractive\Settings\Setting;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $guarded = ['updated_at', 'id'];

    protected $table = 'settings';

    protected $casts = [
        'secret' => 'boolean',
    ];

    public function scopeGroup($query, $groupName)
    {
        return $query->whereGroup($groupName);
    }

    public function getValAttribute($value)
    {
        if ($this->secret) {
            return decrypt($value);
        }
        return $value;
    }
}
