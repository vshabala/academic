<?php

namespace Oro\Bundle\IssueBundle\Tests\Unit\Form\Type;

use Oro\Bundle\IssueBundle\Form\Type\IssueType;

class IssueTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TaskType
     */
    protected $formType;
    protected function setUp()
    {
        $issueTypes = ['Bug' => 'Bug', 'Test' => 'Test', 'Story' => 'Story'];
        $this->formType = new IssueType($issueTypes);
    }
    /**
     * @param array $widgets
     *
     * @dataProvider formTypeProvider
     */
    public function testBuildForm(array $widgets)
    {
        $builder = $this->getMockBuilder('Symfony\Component\Form\FormBuilder')
            ->disableOriginalConstructor()
            ->getMock();
        $builder->expects($this->exactly(9))
            ->method('add')
            ->will($this->returnSelf());

        foreach ($widgets as $key => $widget) {
            $builder->expects($this->at($key))
                ->method('add')
                ->with($this->equalTo($widget))
                ->will($this->returnSelf());
        }
        $this->formType->buildForm($builder, []);
    }

    public function formTypeProvider()
    {
        return [
            'all' => [
                'widgets' => [
                    'summary',
                    'code',
                    'description',
                    'reporter',
                    'assignee',
                    'issueType',
                    'issuePriority',
                    'issueResolution',
                    'relatedIssues',
                ]
            ]
        ];
    }
    public function testGetName()
    {
        $this->assertEquals('oro_issue_issue', $this->formType->getName());
    }
}
