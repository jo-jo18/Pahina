<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key', 'value', 'type', 'group',
    ];

    protected $casts = [
        'value' => 'string',
    ];

    public function getTypedValueAttribute()
    {
        return match ($this->type) {
            'boolean' => (bool) $this->value,
            'integer' => (int) $this->value,
            'float' => (float) $this->value,
            'json' => json_decode($this->value, true),
            default => $this->value,
        };
    }

    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->typed_value : $default;
    }

    public static function set($key, $value, $type = 'text', $group = 'general')
    {
        $setting = self::firstOrNew(['key' => $key]);
        
        if (is_array($value) || is_object($value)) {
            $value = json_encode($value);
            $type = 'json';
        }
        
        $setting->value = $value;
        $setting->type = $type;
        $setting->group = $group;
        $setting->save();
        
        return $setting;
    }
}