<?php

namespace ORO\Bundle\IssueBundle\Tests\Unit\EventListener;

use ORO\Bundle\IssueBundle\Entity\Issue;
use ORO\Bundle\IssueBundle\EventListener\IssueListener;
use Oro\Bundle\NoteBundle\Entity\Note;

use Doctrine\ORM\Event\LifecycleEventArgs;

class NoteListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var NoteListener
     */
    protected $listener;

    /**
     * @var Issue
     */
    private $issue;

    /**
     * @var LifecycleEventArgs
     */
    private $lifecycleEventArgs;

    /**
     * @var Note
     */
    private $note;

    protected function setUp()
    {
        $this->listener = new IssueListener();
        $this->issue = $this->getMockBuilder('ORO\Bundle\IssueBundle\Entity\Issue')
            ->disableOriginalConstructor()
            ->getMock();
        $this->lifecycleEventArgs = $this->getMockBuilder('Doctrine\ORM\Event\LifecycleEventArgs')
            ->disableOriginalConstructor()
            ->getMock();
        $this->note =  $this->getMockBuilder('Oro\Bundle\NoteBundle\Entity\Note')
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected function tearDown()
    {
        unset($this->listener);
        unset($this->lifecycleEventArgs);
        unset($this->issue);
    }
    /**
     * Test postPresist
     */
    public function testPostPersistValid()
    {
        $this->note->expects($this->once())
            ->method('getTarget')
            ->willReturn($this->issue);

        $this->lifecycleEventArgs->expects($this->once())
            ->method('getEntity')
            ->willReturn($this->note);

        $this->issue->expects($this->once())
            ->method('setUpdatedAt');

        $this->listener->postPersist($this->lifecycleEventArgs);
    }
}