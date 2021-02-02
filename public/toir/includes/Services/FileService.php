<?php

class FileService
{

    const PATH = 'storage/app/public/';
    const PATH2 = 'storage/';

    /**
     * @param File $file
     * 
     * @return bool
     */
    public static function isImage(File $file): bool
    {
        $imageMimes = [
            'image/gif',
            'image/jpeg',
            'image/pjpeg',
            'image/png',
            'image/svg+xml',
            'image/tiff',
            'image/vnd.microsoft.icon',
            'image/vnd.wap.wbmp',
            'image/bmp',
            'image/webp',    
        ];
        return in_array($file->mime, $imageMimes);
    }

    /**
     * @param File $file
     * 
     * @return string
     */
    public static function getUrl(File $file): string
    {
        return '../' . self::PATH2 . $file->filename;
    }

    /**
     * @param string $name
     * 
     * @return int|null
     */
    public static function upload($name): ?int
    {
        $fileRequest = $_FILES[$name];

        $fileId = null;

        if(!$fileRequest['error']) {
            $filename = self::getUniqueName($fileRequest['name'], $fileRequest['tmp_name']);

            $resultUpload = move_uploaded_file($fileRequest['tmp_name'], self::getPathToStorage() . $filename);
            if($resultUpload) {
                $fileId = File::create([
                    'original_name' => $fileRequest['name'],
                    'filename' => $filename,
                    'filepath' => self::PATH,
                    'mime' => $fileRequest['type'],
                ]);
            }
        }

        return $fileId;
    }

    /**
     * @param string $name
     * 
     * @return array
     */
    public static function uploadMultiple(string $name): array
    {
        $result = [];

        if(empty($_FILES[$name]) || !is_array($_FILES[$name])) {
            return $result;
        }

        $fileRequest = $_FILES[$name];

        foreach($fileRequest['name'] as $key => $filename) {
            if($fileRequest['error'][$key]) {
                continue;
            }

            $filename = self::getUniqueName($fileRequest['name'][$key], $fileRequest['tmp_name'][$key]);

            $resultUpload = move_uploaded_file($fileRequest['tmp_name'][$key], self::getPathToStorage() . $filename);
            if($resultUpload) {
                $fileId = File::create([
                    'original_name' => $fileRequest['name'][$key],
                    'filename' => $filename,
                    'filepath' => self::PATH,
                    'mime' => $fileRequest['type'][$key],
                ]);

                if($fileId) {
                    $result[] = $fileId;
                }
            }
        }

        return $result;
    }

    private static function getUniqueName($name, $uploadedFile): string
    {
        $ext = pathinfo($name, PATHINFO_EXTENSION);
        $md5File = md5_file($uploadedFile);
        $dir = substr($md5File, 0, 3);

        $storagePath = self::getPathToStorage();

        if(!file_exists($storagePath . $dir)) {
            mkdir($storagePath . $dir);
            chmod($storagePath . $dir, 755);
        }

        return $dir . '/' . $md5File . "." . $ext;
    }

    private static function getPathToStorage(): string
    {
        return $_SERVER["DOCUMENT_ROOT"] . '/../' . self::PATH;
    }

    

}