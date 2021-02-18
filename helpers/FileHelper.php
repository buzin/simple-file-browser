<?php

class FileHelper
{
    private const UNITS = ['bytes', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB'];

    public static function getReadableSize(float $size): string
    {
        $power = $size > 0 ? floor(log($size, 1024)) : 0;
        return sprintf('%s %s', round($size / pow(1024, $power), 2), self::UNITS[$power]);
    }

    public static function clearPath(?string $path): string
    {
        $path = trim($path);
        $path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
        $parts = array_filter(explode(DIRECTORY_SEPARATOR, $path), 'mb_strlen');
        $cleanPath = [];
        foreach ($parts as $part) {
            if ($part != '.' && $part != '..') {
                $cleanPath[] = $part;
            }
        }
        return implode(DIRECTORY_SEPARATOR, $cleanPath);
    }

    public static function getParent(?string $path): ?string
    {
        $parent = null;
        if ($path) {
            $array = explode(DIRECTORY_SEPARATOR, $path);
            if (count($array) > 1) {
                $array = array_slice($array, 0, -1);
                $parent = implode(DIRECTORY_SEPARATOR, $array);
            }
        }
        return $parent;
    }
}