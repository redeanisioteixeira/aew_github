<?php
/**
 * Sec_File
 *
 * @author diego
 *
 */
class Sec_File
{
    public static function getExtension($filename)
    {
		$ext = strtolower(substr($filename, strrpos($filename, '.') + 1));
		return $ext;
    }
}
