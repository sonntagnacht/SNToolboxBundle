<?php
/**
 * umwelt-online-website
 * Created by PhpStorm.
 * File: ValidationHelper.php
 * User: con
 * Date: 19.10.16
 * Time: 11:59
 */

namespace SN\ToolboxBundle\Helper;


use SN\ToolboxBundle\Exception\NotImplementedException;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Blank;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class ValidationHelper
{

    use ContainerAwareTrait;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param $className
     * @param array $groups
     * @return array with FieldName->Constraints[]
     */
    public function getConstraints($className, array $groups = array('default'))
    {
        /**
         * @var $metadata ClassMetadata
         */
        $metadata = $this->container->get('validator')->getMetadataFor($className);

        $allConstraints = array();

        foreach ($metadata->getConstrainedProperties() as $constrainedProperty) {
            $propertyMetadata = $metadata->getPropertyMetadata($constrainedProperty);
            /**
             * @var $constraints Constraint[]
             */
            $constraints = $propertyMetadata[0]->constraints;

            $allConstraints[$constrainedProperty] = array();

            foreach ($constraints as $constraint) {
                $allConstraints[$constrainedProperty][] = $constraint;
            }
        }

        return $allConstraints;
    }

    /**
     * returns an invalid value for a given Symfony Constraint.
     *
     * @param Constraint $constraint
     * @return mixed
     * @throws NotImplementedException
     */
    public static function getInvalidValue(Constraint $constraint)
    {
        switch (get_class($constraint)) {
            case Blank::class:
                return 'foobar';
                break;

            case NotBlank::class:
                return null;
                break;

            case NotNull::class:
                return null;
                break;

            case Collection::class:
                return sha1(mt_rand(0, 999999)); // hoping that the result will not be in the list :>
                break;

            case Email::class:
                return 'foobar';
                break;

            case Type::class:
                /** @var $constraint Type */
                switch ($constraint->type) {
                    case 'string':
                        return 23;
                        break;
                    case 'int':
                        return 'foobar';
                        break;
                    case 'boolean':
                        return 234;
                        break;
                }
                break;

            case Length::class:
                /** @var $constraint Length */
                if ($constraint->max != null && $constraint->min == null) {
                    $max = intval($constraint->max) + 1;

                    return str_repeat('X', $max);
                } elseif ($constraint->max == null && $constraint->min != null) {
                    $min = intval($constraint->min) - 1;
                    if ($min > 0) {
                        return str_repeat('X', $min);
                    }

                    return '';
                } elseif ($constraint->max != null && $constraint->min != null) {
                    $max = intval($constraint->max) + 1;
                    $min = intval($constraint->min) - 1;

                    return mt_rand(0, 1) == 1 ? str_repeat('X', $max) : str_repeat('X', $min);
                }
                break;

            case Range::class:
                /** @var $constraint Range */
                if ($constraint->max != null && $constraint->min == null) {
                    return $constraint->max + 1;
                } elseif ($constraint->max == null && $constraint->min != null) {
                    return $constraint->min - 1;
                } elseif ($constraint->max != null && $constraint->min != null) {
                    return mt_rand(0, 1) == 1 ? $constraint->max + 1 : $constraint->min - 1;
                }
                break;
            default:
                throw new NotImplementedException(
                    sprintf(
                        'Invalid Value for [%s] not implemented yet',
                        get_class($constraint)
                    )
                );
                break;
        }
    }

}