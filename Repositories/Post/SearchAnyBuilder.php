<?php

namespace Modules\Admin\Repositories\Post;

use Modules\Admin\Repositories\Base\SearchAnyInterface;

class SearchAnyBuilder implements SearchAnyInterface
{
    private $searchable =[
        'title',
        'content'
    ];
    private $searchString;

    public function __construct($searchString = null)
    {
        $this->searchString = $searchString;
    }


    public function searchAnyBuilder($query)
    {
        if ($this->searchString) {
            foreach ($this->searchable as $field) {
                $query = $query->orWhere($field, 'LIKE', '%'.$this->searchString.'%');
            }
            return $query;
        }

        return $query;
    }
}
