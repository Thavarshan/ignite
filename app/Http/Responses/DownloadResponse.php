<?php

namespace App\Http\Responses;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DownloadResponse extends Response
{
    /**
     * Create a new file download response.
     *
     * @param \Spatie\MediaLibrary\MediaCollections\Models\Media $content
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public static function dispatchDownload(Media $content): BinaryFileResponse
    {
        return self::create()->download(
            file: $content->getPath(),
            name: $content->getDownloadFilename(),
            headers: ['Content-Type' => $content->mime_type]
        );
    }
}
