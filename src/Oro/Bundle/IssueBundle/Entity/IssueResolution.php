<?php

namespace Oro\Bundle\IssueBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\DataAuditBundle\Metadata\Annotation as Oro;

/**
 * @ORM\Entity
 * @ORM\Table(name="oro_issue_resolution")
 * @Config(
 *      defaultValues={
 *          "grouping"={
 *              "groups"={"dictionary"}
 *          }
 *      }
 * )
 */
class IssueResolution
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=32)
     * @ORM\Id
     */
    protected $name;
    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=255, unique=true)
     */
    protected $label;
    /**
     * @var string
     *
     * @ORM\Column(name="`order`", type="integer")
     */
    protected $order;


    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @param string $name
     *
     * @return IssueResolution
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $label
     *
     * @return IssueResolution
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param integer $order
     *
     * @return IssueResolution
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @return integer
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->label;
    }
}
