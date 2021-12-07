<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Entities\Media;
use App\Helpers\Map\MediaHelper;
use Illuminate\Support\Facades\Storage;

class CreateThumbnail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $media;

    public function __construct(Media $media)
    {
        $this->media = $media;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $media = $this->media;
        $mineTypeArr = explode('/', $media->mine_type);
        if ($mineTypeArr[0] == 'image') {
            $thumbnailConfig = config('images.resize');

            $directory = Storage::disk($media->disk)->path('thumbnails');
            if (!is_dir($directory)) {
                Storage::disk($media->disk)->makeDirectory('thumbnails');
            }

            // get size image
            $oldPathFull = Storage::disk($media->disk)->path($media->path);
            if (file_exists($oldPathFull)) {
                $imageSize = getimagesize($oldPathFull);
                if (is_array($imageSize)) {
                    $imageSizeW = $imageSize[0];
                } else {
                    $imageSizeW = 0;
                }
            } else {
                $imageSizeW = 0;
            }

            // get full name image
            $imageName = $media->uid;
            $arr = explode('/', $media->path);
            $imageFullName = array_pop($arr);

            $thumbnail = [];
            $thumbnailDisk = env('CLOUD_DISK', config('filesystems.default'));
            foreach ($thumbnailConfig as $name => $config) {
                if ($imageSizeW < $config['width']) {
                    $config['width'] = $imageSizeW;
                }
                $thumbnailName = $imageName . '-' . $name;
                $thumbnailFullName = str_replace($imageName, $thumbnailName, $imageFullName);

                $newPath = 'thumbnails/' . $thumbnailFullName;
                $newPathFull = Storage::disk($thumbnailDisk)->path($newPath);

                MediaHelper::createThumbnails($oldPathFull, $newPathFull, $config);
                $thumbnail[$name] = $newPath;
            }

            // update database
            $data = [
                'disk_thumbnail' => $thumbnailDisk,
                'thumbnails' => json_encode($thumbnail)
            ];
            Media::where('id', $media->id)->update($data);
        }
    }
}
