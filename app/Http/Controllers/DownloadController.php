<?php

namespace App\Http\Controllers;

use Spatie\MediaLibrary\MediaCollections\Models\Media;

class DownloadController extends Controller
{
    /**
     * Download the given media file.
     *
     * @param \Spatie\MediaLibrary\MediaCollections\Models\Media $media
     *
     * @return \Spatie\MediaLibrary\MediaCollections\Models\Media
     */
    public function __invoke(Media $media): Media
    {
        return $media;
    }
}
