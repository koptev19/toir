<?php

namespace App\Traits;

use App\Contracts\UploadContract;

trait UploadFileTrait
{
    
    /**
     * @param string $filename
     * @param string|null $field = null
     * @return void
     */
    private function uploadFile(string $filename, ?string $field = null)
    {
        if ($fileRequest = $this->file($filename)) {
            $field = $field ?: $filename . '_id';
            $uploadService = app(UploadContract::class);
            $file = $uploadService->uploadFile($fileRequest);
            $this->$field = $file->id;
        }
    }
    
    /**
     * @param string $filename
     * @param string|null $field = null
     * @return void
     */
    private function uploadFileMultiple(string $filename, ?string $field = null)
    {
        $filesRequest = $this->file($filename);

        if ($filesRequest && count($filesRequest) > 0) {
            $field = $field ?: $filename;
            $this->$field = [];
            $uploadService = app(UploadContract::class);
            foreach($filesRequest as $fileRequest) {
                $file = $uploadService->uploadFile($fileRequest);
                $this->$field[] = $file->id;
            }
        }
    }

}