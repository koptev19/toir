<?php

namespace App\Helpers;

use App\Models\File;
use App\Enums\FileTypeEnum;

/**
 * Class FileHelper
 * @package App\Helpers
 */
class FileHelper
{
    /**
     * @param $file
     * @return null|string
     */
    public static function url($file)
    {
        if (empty($file)) {
            return null;
        }

        if ($file instanceof File) {
            // все норм
        } elseif (is_numeric($file)) {
            $file = File::find($file);

            if (empty($file)) {
                return null;
            }
        }

        return config('filesystems.disks.public.url') . '/' . $file->filename;
    }

    /*
     * @param File
     * return string
     */
    public static function getFullPath(File $file)
    {
        return config('filesystems.disks.' . config('filesystems.default') . '.root') . '/' . $file->filename;
    }

    /*
     * @param File
     * return string
     */
    public static function getMimeType(File $file)
    {
        return mime_content_type(self::getFullPath($file));
    }

    /*
     * @param File
     * return boolean
     */
    public static function isArchive(File $file)
    {
        $mimeType = self::getMimeType($file);
        return in_array($mimeType, FileTypeEnum::MYME_TYPES_ARCHIVE);
    }

    /*
     * @param File
     * return boolean
     */
    public static function isAudio(File $file)
    {
        $mimeType = self::getMimeType($file);
        return in_array($mimeType, FileTypeEnum::MYME_TYPES_AUDIO);
    }

    /*
     * @param File
     * return boolean
     */
    public static function isImage(File $file)
    {
        $mimeType = self::getMimeType($file);
        return in_array($mimeType, FileTypeEnum::MYME_TYPES_IMAGE);
    }

    /*
     * @param File
     * return boolean
     */
    public static function isText(File $file)
    {
        $mimeType = self::getMimeType($file);
        return in_array($mimeType, FileTypeEnum::MIME_TYPES_TEXT);
    }

    /*
     * @param File
     * return boolean
     */
    public static function isVideo(File $file)
    {
        $mimeType = self::getMimeType($file);
        return in_array($mimeType, FileTypeEnum::MYME_TYPES_VIDEO);
    }

    /*
     * @param File
     * return boolean
     */
    public static function isOpenDocument(File $file)
    {
        $mimeType = self::getMimeType($file);
        return in_array($mimeType, FileTypeEnum::MYME_TYPES_OPEN_DOCUMENT);
    }

    /*
     * @param File
     * return boolean
     */
    public static function isWord(File $file)
    {
        $mimeType = self::getMimeType($file);
        return in_array($mimeType, FileTypeEnum::MYME_TYPES_WORD);
    }

    /*
     * @param File
     * return boolean
     */
    public static function isExcel(File $file)
    {
        $mimeType = self::getMimeType($file);
        return in_array($mimeType, FileTypeEnum::MYME_TYPES_EXCEL);
    }

    /*
     * @param File
     * return boolean
     */
    public static function isPowerpoint(File $file)
    {
        $mimeType = self::getMimeType($file);
        return in_array($mimeType, FileTypeEnum::MYME_TYPES_POWERPOINT);
    }

    /*
     * @param File
     * return boolean
     */
    public static function isFlash(File $file)
    {
        $mimeType = self::getMimeType($file);
        return in_array($mimeType, FileTypeEnum::MYME_TYPES_FLASH);
    }

    /*
     * @param File
     * return boolean
     */
    public static function isPdf(File $file)
    {
        $mimeType = self::getMimeType($file);
        return in_array($mimeType, FileTypeEnum::MYME_TYPES_PDF);
    }

    /*
     * @param File
     * return string
     */
    public static function getFAIconClass(File $file)
    {
        if (self::isWord($file)) {
            return 'fa-file-word-o';
        }

        if (self::isExcel($file)) {
            return 'fa-file-excel-o';
        }

        if (self::isPowerpoint($file)) {
            return 'fa-file-powerpoint-o';
        }

        if (self::isPdf($file)) {
            return 'fa-file-pdf-o';
        }

        if (self::isImage($file)) {
            return 'fa-file-image-o';
        }

        if (self::isArchive($file)) {
            return 'fa-file-archive-o';
        }

        if (self::isAudio($file)) {
            return 'fa-file-audio-o';
        }

        if (self::isVideo($file)) {
            return 'fa-file-video-o';
        }

        return 'fa-file';
    }

    /*
     * @param File
     * return string
     */
    public static function getSizeFormat(File $file)
    {
        $size = $file->size . ' б';

        if ($file->size < 1024 * 1024) {
            $size = round($file->size / 1024 ) . ' Кб';
        } elseif ($file->size < 1024 * 1024 * 1024) {
            $size = round($file->size / (1024 * 1024) ) . ' Мб';
        } elseif ($file->size < 1024 * 1024 * 1024 * 1024) {
            $size = round($file->size / (1024 * 1024 * 1024) ) . ' Гб';
        } elseif ($file->size < 1024 * 1024 * 1024 * 1024 * 1024) {
            $size = round($file->size / (1024 * 1024 * 1024 * 1024) ) . ' Тб';
        }

        return $size;
    }
}