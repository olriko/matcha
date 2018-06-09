<?php

namespace App\Seed;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'main'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
