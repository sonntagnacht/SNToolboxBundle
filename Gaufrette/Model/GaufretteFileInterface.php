<?php
/**
 * sonntagnacht/toolbox-bundle
 * Created by PhpStorm.
 * File: GaufretteFileInterface.php
 * User: con
 * Date: 27.01.16
 * Time: 14:46
 */

namespace SN\ToolboxBundle\Gaufrette\Model;

use Symfony\Component\HttpFoundation\File\File;

/**
 * Interface GaufretteFileInterface
 *
 * @package SN\ToolboxBundle\Gaufrette\Model
 */
interface GaufretteFileInterface
{

    /**
     * @return File|\SplFileInfo
     */
    public function getFile();

    /**
     * @return String
     */
    public function getFileName();

    /**
     * returns the gaufrette file path
     *
     * @return string
     */
    public function getGaufretteFilepath();

    /**
     * returns the gaufrette filesystem of the
     *
     * @return string
     */
    public static function getGaufretteFilesystem();

}