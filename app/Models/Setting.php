<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'group',
        'description',
        'type',
    ];

    public static function get($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set($key, $value)
    {
        return static::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    public function getValueAttribute($value)
    {
        if ($this->type === 'boolean') {
            return (bool) $value;
        }
        if ($this->type === 'integer') {
            return (int) $value;
        }
        if ($this->type === 'float') {
            return (float) $value;
        }
        return $value;
    }
}
