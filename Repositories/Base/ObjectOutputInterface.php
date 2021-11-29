<?php


namespace Modules\Admin\Repositories\Base;

interface ObjectOutputInterface
{
    public function queryBuilder($query);

    public function output($query);
}
