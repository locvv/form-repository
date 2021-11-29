<?php


namespace Modules\Admin\Repositories\Base;

use Illuminate\Database\Eloquent\Builder;
use Prettus\Repository\Eloquent\BaseRepository;
use \Modules\Admin\Repositories\Base\CollectionOutputInterface as CollectionOutputInterface;
use \Modules\Admin\Repositories\Base\ObjectOutputInterface as ObjectOutputInterface;

abstract class AdminRepositoryEloquent extends BaseRepository
{
    public function conditionBuilder(Builder $query, array $condition): Builder
    {
        if ($condition) {
            foreach ($condition as $condi) {
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

            return $query->paginate($perPage);
        } else {
            return $query->get();
        }
        return $query;
    }


    public function searchAnyBuilder($query, $searchString, $searchFields)
    {
        foreach ($searchFields as $field) {
            $query = $query->orWhere($field, 'LIKE', '%'.$searchString.'%');
        }
        return $query;
    }

    public function collectionOutput($collection, CollectionOutputInterface $collectionOutput)
    {
        return $collectionOutput->output($collection);
    }

    public function objectOutput($object, ObjectOutputInterface $objectOutput)
    {
        return $objectOutput->output($object);
    }
}
