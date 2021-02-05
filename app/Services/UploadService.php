<?php

namespace App\Services;

use App\Contracts\UploadContract;
use App\Models\File;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;

class UploadService implements UploadContract
{
    protected $disk = null;

    /**
     * @return string
     */
    public function getDisk(): string
    {
        return $this->disk ?? config('filesystems.default');
    }

    /**
     * @param string $disk
     *
     * @return UploadService
     */
    public function setDisk(string $disk): UploadService
    {
        $this->disk = $disk;

        return $this;
    }

    /**
     * @param \Illuminate\Http\UploadedFile $uploadedFile
     * @param array $extensions
     *
     * @return \App\Models\File
     * @throws \Throwable
     */
    public function uploadFile(UploadedFile $uploadedFile, array $extensions = []): File
    {
        if (count($extensions) && !in_array($uploadedFile->clientExtension(), $extensions)) {
            abort(
                Response::HTTP_UNPROCESSABLE_ENTITY, 'Недопустимый тип файла. Разрешены: ' .
                join(', ', $extensions)
            );
        }

        return $this->upload($uploadedFile, $this->getDisk());
    }

    /**
     * @param UploadedFile $uploadedFile
     * @param string|null $disk = null
     *
     * @return \App\Models\File
     * @throws \Throwable
     */
    public function upload(UploadedFile $uploadedFile, ?string $disk = null) : File
    {
        $filePath = $uploadedFile->getPathname();
        $originName = $uploadedFile->getClientOriginalName();

        if (empty($disk)) {
            $disk = $this->getDisk();
        }

        if (app()->environment() != 'production') {
            $disk = 'public';
        }

        $maxSize = config('filesystems.max_size_upload');
        if (filesize($filePath) > $maxSize) {
            abort(Response::HTTP_UNPROCESSABLE_ENTITY, 'Не допустимый размер файла, max ' . round($maxSize / 1024 / 1024, 2) . "Mb");
        }

        $ext = pathinfo($originName, PATHINFO_EXTENSION);
        $md5File = md5_file($filePath);
        $dir = substr($md5File, 0, 3);
        $fileName = $dir . DIRECTORY_SEPARATOR . $md5File . "." . $ext;

        $fo = fopen($filePath, 'r');
        \Storage::disk($disk)->put($fileName, $fo, 'public');
        fclose($fo);

        $file = new File();
        $file->filename = $fileName;
        $file->original_name = $originName;
        $file->mime = $uploadedFile->getMimeType();
        $file->saveOrFail();

        return $file;
    }
}