<?php
/**
 * sonntagnacht/toolbox-bundle
 * Created by PhpStorm.
 * File: GaufretteHelper.php
 * User: con
 * Date: 27.01.16
 * Time: 17:05
 */

namespace SN\ToolboxBundle\Gaufrette;

use SN\ToolboxBundle\Gaufrette\Model\GaufretteFileInterface;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Class GaufretteHelper
 *
 * @package SN\ToolboxBundle\Gaufrette
 */
class GaufretteHelper
{

    /**
     * returns the filename without the *.extension
     *
     * @param string $name
     * @return string
     */
    public static function getFilenameWithoutExtension($name)
    {
        try {
            return pathinfo($name, PATHINFO_FILENAME);
        } catch (\Exception $e) {
            $parts = explode('.', $name);
            array_pop($name);

            return implode('.', $parts);
        }
    }

    /**
     * @param File $file
     * @param string $format
     * @return string
     */
    public static function getFilenameForFormat(File $file, $format)
    {
        $extension = pathinfo($file->getFileName(), PATHINFO_EXTENSION);

        return sprintf(
            "%s.%s.%s",
            self::getFilenameWithoutExtension($file->getFilename()),
            $format,
            $extension
        );
    }

    /**
     * @param string $fileSystem
     * @param string $fileName
     * @return string
     */
    public static function getPathForFilename($fileSystem, $fileName)
    {
        return sprintf('gaufrette://%s/%s', $fileSystem, $fileName);
    }

    /**
     * @param string $prefix
     * @return string
     */
    public static function getTmpFilename($prefix)
    {
        return tempnam(sys_get_temp_dir(), $prefix);
    }

    /**
     * @param GaufretteFileInterface $entity
     * @param bool $withFilename
     * @return mixed
     */
    public static function getSubFilepath(GaufretteFileInterface $entity, $withFilename = true)
    {
        $path            = $entity->getGaufretteFilepath();
        $gaufrettePrefix = sprintf('gaufrette://%s', $entity::getGaufretteFilesystem());

        $subPath = str_replace($gaufrettePrefix, '', $path);

        if (false === $withFilename) {
            $subPath = explode('/', $subPath);
            if (count($subPath) > 0) {
                array_pop($subPath);
                $subPath = implode('/', $subPath);
            }
        }

        return $subPath;
    }

}
