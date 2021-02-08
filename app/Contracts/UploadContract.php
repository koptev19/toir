<?php

namespace App\Contracts;

use App\Models\File;
use App\Services\UploadService;
use Illuminate\Http\UploadedFile;

/**
 * Interface UploadContract
 * @package App\Contract
 */
interface UploadContract
{
    public function getDisk(): string;
    public function setDisk(string $disk): UploadService;
    public function uploadFile(UploadedFile $uploadedFile, array $extensions = []): File;
    public function upload(UploadedFile $uploadedFile, ?string $disk = null) : File;
}