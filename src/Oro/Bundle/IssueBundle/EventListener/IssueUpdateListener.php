<?php
namespace Oro\Bundle\IssueBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Oro\Bundle\NavigationBundle\Content\TopicSender;
use Oro\Bundle\IssueBundle\Entity\Issue;

class IssueUpdateListener
{
    /** @var TopicSender */
    protected $sender;

    /**
     * @param TopicSender $sender
     */
    public function __construct(TopicSender $sender)
    {
        $this->sender = $sender;
    }

    public function postUpdate(Issue $issue, LifecycleEventArgs $event)
    {
        $generator = $this->sender->getGenerator();
        $data = ['name' => 'Oro_Bundle_IssueBundle_Entity_Issue'];
        $this->sender->send($generator->generate($data, true));
    }
}
