<?php

namespace Bzzix\PageBuilder\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;

class Pagebuilder extends Model
{
    use SoftDeletes;
    use Sluggable;

    protected $fillable = [
        'title', 
        'slug', 
        'content', 
        'user_id', 
        'status',
        'html',
        'css',
        'components'
    ];

    protected $casts = [
        'components' => 'json'
    ];

    protected $dates = ['deleted_at'];

    /**
     * Return the sluggable configuration array for this model.
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    /**
     * Get the user that owns the page.
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
