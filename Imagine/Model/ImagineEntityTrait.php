<?php
/**
 * sonntagnacht/toolbox-bundle
 * Created by PhpStorm.
 * File: ImagineEntityTrait.php
 * User: con
 * Date: 27.01.16
 * Time: 17:24
 */

namespace SN\ToolboxBundle\Imagine\Model;

/**
 * Class ImagineEntityTrait
 *
 * @package SN\ToolboxBundle\Imagine\Model
 */
trait ImagineEntityTrait
{
    /**
     * @param $format
     * @return mixed
     * @throws \Exception
     */
    public function getFormat($format)
    {
        try {
            if (array_key_exists($format, $this->formats)) {
                return $this->formats[$format];
            } else {
                throw new \InvalidArgumentException(sprintf(
                    'Format %s does not exist for %s %s',
                    $format,
                    get_class($this),
                    $this->getId()
                ));
            }
        } catch (\Exception $e) {
            throw new \Exception(sprintf("invalid format %s %s", get_class($this), $format), 0, $e);
        }
    }

    /**
     * @inheritdoc
     */
    public function getFormats()
    {
        $formats = array();
        foreach ($this->formats as $name => $properties) {
            $formats[$name] = $properties;
        }

        return $formats;
    }
}
