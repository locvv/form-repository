<?php

namespace Modules\Admin\Repositories\Post;

use Illuminate\Database\Eloquent\Builder;
use Modules\Admin\Repositories\Base\CollectionOutputInterface;
use Modules\Admin\Repositories\Base\SearchAnyInterface;

class BaseIndexCollectionOutput implements CollectionOutputInterface
{


    /**
     * @var array
     */
    private $condition;
    /**
     * @var array
     */
    private $orderBy;
    /**
     * @var false
     */
    private $paginate;
    /**
     * @var int
     */
    private $take;
    /**
     * @var int
     */
    private $offset;
    /**
     * @var null
     */
    private $searchAny;
    /**
     * @var array
     */
    private $searchColumns;

    public function __construct(
        array $condition = [],
        array $orderBy = [],
        $paginate = false,
        int $take = 15,
        int $offset = 0,
        SearchAnyInterface $searchAny = null
    ) {
        $this->condition = $condition;
        $this->orderBy = $orderBy;
        $this->paginate = $paginate;
        $this->take = $take;
        $this->offset = $offset;
        $this->searchAny = $searchAny;
    }

    public function queryBuilder($query)
    {
        $query = $query->with('categories')
        ->with('reporter');
        //Xử lý condition ở đây
        if ($this->condition) {
            $query = $this->conditionBuilder($query, $this->condition);
        }

        if ($this->searchAny) {
            $query = $query->where(function ($q) {
                $q = $this->searchAny->searchAnyBuilder($q);
            });
        }

        return $this->orderBuilder($query, $this->orderBy);
    }
    public function conditionBuilder(Builder $query, array $condition): Builder
    {
        if ($condition) {
            foreach ($condition as $key => $condi) {
                if ($condi[0] == 'categories') {
                    $query->whereHas('categories', function ($q) use ($condi) {
                        $q->whereIn('id', $condi[1]) ;
                    });
                    continue;
                }
                if (count($condi) == 2) {
                    $query->where($condi[0], $condi[1]);
                } else {
                    $query->where($condi[0], $condi[1], $condi[2]);
                }
            }
        }
        return $query;
    }

    public function orderBuilder($query, $orderBy)
    {
        if ($orderBy) {
            foreach ($orderBy as $order) {
                $query->orderBy($order[0], $order[1]);
            }
        }
        return $query;
    }

    public function paginateBuilder($query, $paginate, int $take = 15, $offset = 0)
    {
        if ($take) {
            $query->take($take);
        }
        if ($offset) {
            $query->skip($offset);
        }

        if ($paginate) {
            $perPage = ($take) ? $take : 15;

            return $query->paginate($perPage)->withQueryString();
        } else {
            return $query->get();
        }
        return $query;
    }

    public function output($query)
    {
        return $this->paginateBuilder($query, $this->paginate, $this->take, $this->offset);
    }
}
