<?php

namespace App\Facades;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\Response;

class Docs
{
    /**
     * Get the documentation content.
     *
     * @param string $file
     *
     * @return string
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public static function get(string $file): string
    {
        $file = $file !== 'index' ? $file . '/index' : $file;

        try {
            return File::get(public_path('/docs/' . $file . '.html'));
        } catch (FileNotFoundException $e) {
            logger()->error($e->getMessage(), [
                'file' => $file,
                'exception' => $e,
            ]);

            abort(Response::HTTP_NOT_FOUND, 'The requested documentation does not exist.'); // phpcs:ignore
        }
    }
}
