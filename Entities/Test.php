<?php

namespace Modules\Admin\Entities;
use Illuminate\Database\Eloquent\Model;
use Traits\Slugable;

class Test extends Model
{
    use Slugable;

    protected $fillable = [
        'name','phone','test',
    ];

    public function getFillable()
    {
        return $this->fillable;
    }
}
