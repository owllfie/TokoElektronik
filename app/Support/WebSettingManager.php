<?php

namespace App\Support;

use App\Models\WebSetting;
use Illuminate\Support\Facades\Schema;

class WebSettingManager
{
    public static function defaults(): array
    {
        return [
            'company_name' => 'Electro',
            'company_mark' => 'E',
        ];
    }

    public static function all(): array
    {
        $defaults = static::defaults();

        if (! Schema::hasTable('web_settings')) {
            return $defaults;
        }

        return array_merge(
            $defaults,
            WebSetting::query()->pluck('setting_value', 'setting_key')->all()
        );
    }
}
