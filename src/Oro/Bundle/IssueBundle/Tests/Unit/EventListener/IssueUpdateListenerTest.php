<?php

namespace OroAcademic\Bundle\IssueBundle\Tests\Unit\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;

use Oro\Bundle\NavigationBundle\Content\TopicSender;
use Oro\Bundle\IssueBundle\Entity\Issue;
use Oro\Bundle\IssueBundle\EventListener\IssueUpdateListener;

class IssueUpdateListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TopicSender
     */
    protected $sender;
    /**
     * @var IssueEntityListener
     */
    protected $listener;

    /**
     * {@inheritdoc}
     */

    protected function setUp()
    {
        $this->sender = $this
            ->getMockBuilder('Oro\Bundle\NavigationBundle\Content\TopicSender')
            ->disableOriginalConstructor()
            ->getMock();
        $this->listener = new IssueUpdateListener($this->sender);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->sender);
        unset($this->listener);
    }

    public function testPostUpdate()
    {
        $generator = $this
            ->getMockBuilder('Oro\Bundle\NavigationBundle\Content\TagGeneratorChain')
            ->disableOriginalConstructor()
            ->getMock();

        $generator->expects($this->once())->method('generate')->willReturn([]);
        $this->sender->expects($this->once())->method('getGenerator')->willReturn($generator);
        $this->sender->expects($this->once())->method('send');

        $issueMock = $this->getMock('Oro\Bundle\IssueBundle\Entity\Issue');
        $lifecycleEventArgs = $this->getMockBuilder('Doctrine\ORM\Event\LifecycleEventArgs')
            ->disableOriginalConstructor()
            ->getMock();
        $lifecycleEventArgs->expects($this->any())
            ->method('getEntity')
            ->willReturn($issueMock);
        $this->listener->postUpdate($issueMock, $lifecycleEventArgs);
    }
}
