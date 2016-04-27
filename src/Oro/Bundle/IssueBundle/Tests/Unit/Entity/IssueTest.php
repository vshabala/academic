<?php
namespace Oro\Bundle\IssueBundle\Tests\Unit\Entity;

use Oro\Bundle\IssueBundle\Entity\Issue;

class IssueTest extends \PHPUnit_Framework_TestCase
{
    protected $assignee;
    protected $reporter;
    protected $organization;

    public function setUp()
    {
        parent::setUp();
        $this->assignee = $this->getMock('Oro\Bundle\UserBundle\Entity\User');
        $this->reporter = $this->getMock('Oro\Bundle\UserBundle\Entity\User');
        $this->organization = $this->getMock('Oro\Bundle\OrganizationBundle\Entity\Organization');
    }
    public function testCreate()
    {
        new Issue();
    }
    /**
     * @dataProvider settersAndGettersDataProvider
     */
    public function testSettersAndGetters($property, $value)
    {
        $obj = new Issue();
        call_user_func_array(array($obj, 'set' . ucfirst($property)), array($value));
        $this->assertEquals($value, call_user_func_array(array($obj, 'get' . ucfirst($property)), array()));
    }
    public function settersAndGettersDataProvider()
    {

        return array(
            array('code', 'AA-1111'),
            array('summary', 'Issue 1 - entity test'),
            array('issueType', 'Task'),
            array('description', 'Issue Description'),
            array('issuePriority', $this->getMock('ORO\Bundle\IssueBundle\Entity\issuePriority')),
            array('reporter', $this->reporter),
            array('assignee', $this->assignee),
            array('organization', $this->organization),
            array('createdAt', new \DateTime()),
            array('updatedAt', new \DateTime())

        );
    }

    public function testToString()
    {
        $entity = new Issue();
        $this->assertEmpty((string)$entity);
        $entity->setCode('AA-1112');
        $entity->setSummary('Summary');
        $this->assertEquals('AA-1112: Summary', (string)$entity);
    }


    public function tearDown()
    {
        parent::tearDown();
        unset($this->organization);
        unset($this->assignee);
        unset($this->reporter);
    }
}
