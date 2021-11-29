<?php


namespace Modules\Admin\Repositories\Base;

interface CollectionOutputInterface
{
    public function queryBuilder($query);
    public function output($query);
}
