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
interface GaufretteImagineFileInterface extends GaufretteFileInterface
{

    /**
     * the required format array
     *
     * @param string $format
     * @return array
     * @throws \Exception for unknown format
     */
    public function getFormat($format);

    /**
     * an array of all available formats
     *
     * @return array
     */
    public function getFormats();

    /**
     * @param string $format
     * @return boolean
     */
    public function isOriginalFormat($format);

}