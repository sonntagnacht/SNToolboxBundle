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
     * returns the sub-directory path within the gaufrette filesystem or '' when its located directly in it
     *
     * info: you may implement it by yourself or use SN\ToolboxBundle\Gaufrette\GaufretteHelper::getSubFilepath
     *
     * @param bool $withFilename
     * @return string
     */
    public function getSubFilepath($withFilename = false);

    /**
     * returns the gaufrette filesystem of the entity
     *
     * @return string
     */
    public static function getGaufretteFilesystem();

}
