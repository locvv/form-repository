<?php

namespace Modules\Admin\Traits;

use Modules\Admin\Entities\Slug;

trait Slugable
{
    public function slug()
    {
        return $this->morphOne(
            Slug::class,
            'slugable'
        );
    }
}
