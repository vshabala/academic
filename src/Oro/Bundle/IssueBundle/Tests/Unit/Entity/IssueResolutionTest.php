<?php
namespace Oro\Bundle\IssueBundle\Tests\Unit\Entity;

use Oro\Bundle\IssueBundle\Entity\IssueResolution;


class IssueResolutionTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        new IssueResolution('fixed');
    }
    /**
     * @dataProvider settersAndGettersDataProvider
     */
    public function testSettersAndGetters($property, $value)
    {
        $obj = new IssueResolution('low');
        call_user_func_array(array($obj, 'set' . ucfirst($property)), array($value));
        $this->assertEquals($value, call_user_func_array(array($obj, 'get' . ucfirst($property)), array()));
    }
    public function testGetName()
    {
        $expected = 'fixed';
        $entity = new IssueResolution($expected);
        $this->assertEquals($expected, $entity->getName());
    }
    public function testToString()
    {
        $expected = 'Fixed test';
        $entity = new IssueResolution('Fixed');
        $entity->setLabel($expected);
        $this->assertEquals($expected, (string)$entity);
    }
    public function settersAndGettersDataProvider()
    {
        return array(
            array('label', 'Fixed test')
        );
    }
}