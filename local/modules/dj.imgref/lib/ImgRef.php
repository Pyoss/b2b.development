<?php

namespace DJScripts;

use Bitrix\Main\IO\Directory;
use Bitrix\Main\IO\File;

class ImgRef
{

    static public function getNewSizes($sourceWidth, $sourceHeight, $sizeArray): array
    {
        $ratio = $sourceWidth / $sourceHeight;
        if ($sourceWidth > $sourceHeight) {
            $newWidth = $sizeArray['width'];
            $newHeight = $newWidth / $ratio;
        } else {
            $newHeight = $sizeArray['height'];
            $newWidth = $newHeight * $ratio;
        }
        return [$newWidth, $newHeight];
    }

    static private function createImage($sourceImg, $sizeArray, $transparent)
    {

        // get dimens of image
        $sourceWidth = imagesx($sourceImg);
        $sourceHeight = imagesy($sourceImg);
        list($newWidth, $newHeight) = self::getNewSizes($sourceWidth, $sourceHeight, $sizeArray);
        // create a canvas

        $im = imagecreatetruecolor($newWidth, $newHeight);
        if ($transparent) {
            imageAlphaBlending($im, false);
            imageSaveAlpha($im, true);

            // By default, the canvas is black, so make it transparent
            $mod = imagecolorallocatealpha($im, 0, 0, 0, 127);
        } else {
            $mod = imagecolorallocate($im, 255, 255, 255);
        }

        imagefilledrectangle($im, 0, 0, $newWidth - 1, $newHeight - 1, $mod);

        // copy png to canvas

        imagecopyresampled($im, $sourceImg, 0, 0, 0, 0, $newWidth, $newHeight, $sourceWidth, $sourceHeight);

        // lastly, save canvas as a webp
        return $im;

    }

    static public function optimizeImg($file_id, $size_array = array(), $transparent = true, $jpeg_quality = 75)
    {
        $file = \CFile::GetByID($file_id)->GetNext();
        $file['RESOURCE'] = substr($file['FILE_NAME'], 0, strrpos($file['FILE_NAME'], "."));;
        $resizedPath =
            'upload/dj_resized_cache/' .
            $file['SUBDIR'] .
            '/' . $file['RESOURCE'] . '/' . implode('_', array_values($size_array)) .
            '/' . ($transparent ? 'alpha/' : 'white/');

        $cachedDefault = File::isFileExists($_SERVER['DOCUMENT_ROOT'] . $resizedPath . $file['RESOURCE'] . ($transparent ? '.png' : '.jpg'));
        $cachedWebp = File::isFileExists($_SERVER['DOCUMENT_ROOT'] . $resizedPath . $file['RESOURCE'] . '.webp');
        if (!$cachedDefault || !$cachedWebp) {
            Directory::createDirectory($_SERVER['DOCUMENT_ROOT'] . $resizedPath);
            if ($file['CONTENT_TYPE'] == 'image/png') {
                $sourceImg = imagecreatefrompng($_SERVER['DOCUMENT_ROOT'] . \CFile::GetPath($file_id));
                if (!$sourceImg) {
                    return false;
                }
            } elseif ($file['CONTENT_TYPE'] == 'image/jpeg') {
                $sourceImg = imagecreatefromjpeg($_SERVER['DOCUMENT_ROOT'] . \CFile::GetPath($file_id));
                if (!$sourceImg) {
                    return false;
                }
            }
            $outputImage = self::createImage($sourceImg, $size_array, $transparent);
        }
        if (!$cachedWebp){
            imagewebp($outputImage, $_SERVER['DOCUMENT_ROOT'] .$resizedPath . $file['RESOURCE'] . '.webp');
        }
        if (!$cachedDefault) {
            if ($transparent) {
                imagepng($outputImage, $_SERVER['DOCUMENT_ROOT'] .$resizedPath . $file['RESOURCE'] . '.png');
            } else {
                imagejpeg($outputImage, $_SERVER['DOCUMENT_ROOT'] .$resizedPath . $file['RESOURCE'] . '.jpg');
            }
        }
        if ($outputImage){
            imagedestroy($outputImage);
        }
        $arResult = array('webp' => array('path' => '/' . $resizedPath . $file['RESOURCE'] . '.webp', 'cached' => $cachedWebp), 'default' => array('path' => '/' . $resizedPath . $file['RESOURCE'] . ($transparent ? '.png' : '.jpg'), 'cached' => $cachedDefault));
        if( strpos( $_SERVER['HTTP_ACCEPT'], 'image/webp' ) !== false ) {
            $arResult['optimal'] = 'webp';
        } else {
            $arResult['optimal'] = 'default';
        }
        return $arResult;
    }
}