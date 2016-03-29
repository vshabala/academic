<?php
namespace Oro\Bundle\IssueBundle\EventListener;

use Doctrine\ORM\EntityManager;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Oro\Bundle\NoteBundle\Entity\Note;
use Oro\Bundle\IssueBundle\Entity\Issue;

class IssueListener
{
     public function postPersist(LifecycleEventArgs $args)
     {
          $entity = $args->getEntity();

          if (!$entity instanceof Note) {
               return;
          }

          $this->setUpdatedProperties($entity, $args->getEntityManager());
      }

     /**
     * @param Note $note
     * @param EntityManager $entityManager
      */
    protected function setUpdatedProperties(Note $note, EntityManager $entityManager, $update = false)
    {
        $newUpdatedAt = new \DateTime('now', new \DateTimeZone('UTC'));
        $issue = $note->getTarget();

        $issue->setUpdatedAt($newUpdatedAt);


    }
}