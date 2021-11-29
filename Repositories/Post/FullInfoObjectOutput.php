<?php

namespace Modules\Admin\Repositories\Post;

use Modules\Admin\Repositories\Base\ObjectOutputInterface;

class FullInfoObjectOutput implements ObjectOutputInterface
{

    public function queryBuilder($query)
    {

        return $query->with('categories')
            ->with('prefecture')
            ->with('municipality')
            ->with('image')
            ->with('reporter')
            ->with('comments')
            ->with('likes')
            ->with('slug');
    }

    public function output($query)
    {
        $post = $query->first();

        if ($post) {
            if ($post->image) {
                $post->image_url = \Storage::disk($post->image->disk)->url($post->image->path);
            }
            if ($post->geo_lat && $post->geo_lng) {
                $post->geo_lat_lng = $post->geo_lat.', '.$post->geo_lng;
            }
            if ($post->slug) {
                $post->slug_name = $post->slug->slug;
            }
        }

        return $post;
    }
}
