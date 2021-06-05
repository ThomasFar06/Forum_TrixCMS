<?php

namespace Extensions\Plugins\Forum_arckene__421339390\App\Models;

use Geeky\Database\CacheQueryBuilder;
use Illuminate\Database\Eloquent\Model;

class ForumIconType extends Model
{
    use CacheQueryBuilder;
    protected $fillable = ['name', 'website', 'format', 'import', 'type', 'updated_at'];
    protected $table = 'forum__icon_type';

    public function create(array $attributes = [])
    {
        return parent::create($attributes); // TODO: Change the autogenerated stub
    }
}
