<?php

namespace Modules\Admin\Entities;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    protected $fillable = [
        'name','phone','test',
    ];
    public function getFillable()
    {
        return $this->fillable;
    }
}
