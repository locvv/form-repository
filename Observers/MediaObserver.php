<?php

namespace App\Observers;

use App\Entities\Media;
use App\Jobs\CreateThumbnail;
use Illuminate\Support\Facades\Storage;

class MediaObserver
{
    /**
     * Handle the media "created" event.
     *
     * @param  \App\Media  $media
     * @return void
     */
    public function created(Media $media)
    {
        CreateThumbnail::dispatch($media);
    }

    /**
     * Handle the media "updated" event.
     *
     * @param  \App\Media  $media
     * @return void
     */
    public function updated(Media $media)
    {
        //
    }

    /**
     * Handle the media "deleted" event.
     *
     * @param  \App\Media  $media
     * @return void
     */
    public function deleted(Media $media)
    {
        $thumbnails = json_decode($media->thumbnails);
        foreach ($thumbnails as $type => $path) {
            if ($path != null) {
                Storage::disk($media->disk_thumbnail)->delete($path);
            }
        }
    }

    /**
     * Handle the media "restored" event.
     *
     * @param  \App\Media  $media
     * @return void
     */
    public function restored(Media $media)
    {
        //
    }

    /**
     * Handle the media "force deleted" event.
     *
     * @param  \App\Media  $media
     * @return void
     */
    public function forceDeleted(Media $media)
    {
        //
    }
}
