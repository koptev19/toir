<?php

namespace App\Helpers;

use App\Models\File;

/**
 * Class ImageHelper
 * @package App\Helpers
 */
class ImageHelper
{


    /**
     * @param File|null $file
     * @param array $options = []
     *
     * @return array
     */
    public static function imgTag(?File $file, array $options = [])
    {
        $tag = "";

        if($file && $url = FileHelper::url($file)) {
            $class = !empty($options['class']) ? 'class="' . $options['class'] . '"' : '';
            $style = !empty($options['style']) ? 'style="' . $options['style'] . '"' : '';
            $tag = '<img src="' . $url . '" ' . $class . ' ' . $style . '>';
        }

        return $tag;
    }

    public static function linkImgTag(?File $file, array $linkOptions = [], array $imgOptions = [])
    {
        $tag = "";

        if($file && $url = FileHelper::url($file)) {
            $class = !empty($linkOptions['class']) ? 'class="' . $linkOptions['class'] . '"' : '';
            $style = !empty($linkOptions['style']) ? 'style="' . $linkOptions['style'] . '"' : '';
            $tag = '<a href="' . $url . '" ' . $class . ' ' . $style . ' target=_blank>' . self::imgTag($file, $imgOptions) . '</a>';
        }

        return $tag;
    }

}