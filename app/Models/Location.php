<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = ['parent_id', 'name', 'type'];

    // Ye row ka parent (upar wali location)
    public function parent()
    {
        return $this->belongsTo(Location::class, 'parent_id');
    }

    // Is row ke direct bachche (neeche wali locations)
    public function children()
    {
        return $this->hasMany(Location::class, 'parent_id');
    }

    // "Islamabad → Zone 1 → G-5" jaisi readable string banata hai
    public function getFullPathAttribute(): string
    {
        $path = collect([$this->name]);
        $parent = $this->parent;

        while ($parent) {
            $path->prepend($parent->name);
            $parent = $parent->parent;
        }

        return $path->implode(' → ');
    }

    // Is node ki id + uske sare (nested) descendants ki ids ek flat array mein.
    // Filter ke waqt kaam aata hai: city select ki to uske sare areas/zones bhi aa jayen.
    public function descendantIds(): array
    {
        $ids = [$this->id];

        foreach ($this->children as $child) {
            $ids = array_merge($ids, $child->descendantIds());
        }

        return $ids;
    }

    public function affiliators()
    {
        return $this->hasMany(Affiliator::class, 'location_id');
    }

}
