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
        'type',
        'description',
        'sort_order',
    ];

    /**
     * Cast the raw text value to its proper PHP type based on the type column.
     * Used internally by static helpers; not auto-applied to the model.
     */
    public function getCastedValueAttribute()
    {
        return match ($this->type) {
            'integer' => (int) $this->value,
            'decimal' => (float) $this->value,
            'boolean' => filter_var($this->value, FILTER_VALIDATE_BOOLEAN),
            default   => $this->value,
        };
    }

    /**
     * Retrieve a single setting value by key with type-cast applied.
     *
     * @param  string  $key
     * @param  mixed   $default  Returned if the key is not found.
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->casted_value : $default;
    }

    /**
     * Retrieve all settings in a group as an associative array,
     * keyed by setting key with cast values.
     *
     * @param  string  $group
     * @return array<string, mixed>
     */
    public static function group(string $group): array
    {
        return static::where('group', $group)
            ->orderBy('sort_order')
            ->get()
            ->mapWithKeys(fn ($s) => [$s->key => $s->casted_value])
            ->toArray();
    }

    /**
     * Update a setting value by key. Returns true if updated, false if key not found.
     */
    public static function set(string $key, $value): bool
    {
        $setting = static::where('key', $key)->first();
        if (!$setting) {
            return false;
        }
        $setting->value = is_bool($value) ? ($value ? '1' : '0') : (string) $value;
        return $setting->save();
    }

    /**
     * Scope: order by sort_order ascending. Mirrors pattern used by Service & ExpenseCategory models.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
