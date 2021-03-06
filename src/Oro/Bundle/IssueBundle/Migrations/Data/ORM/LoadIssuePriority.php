<?php

namespace Oro\Bundle\IssueBundle\Migrations\Data\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Oro\Bundle\IssueBundle\Entity\IssuePriority;

class LoadIssuePriority extends AbstractFixture
{
    /**
     * @var array
     */
    protected $data = array(
        array(
            'label' => 'Low',
            'name' => 'low',
            'order' => 1,
        ),
        array(
            'label' => 'Normal',
            'name' => 'normal',
            'order' => 2,
        ),
        array(
            'label' => 'High',
            'name' => 'high',
            'order' => 3,
        )
    );
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->data as $priority) {
            if (!$this->isPriorityExist($manager, $priority['name'])) {
                $entity = new IssuePriority($priority['name']);
                $entity->setLabel($priority['label']);
                $entity->setOrder($priority['order']);
                $manager->persist($entity);
            }
        }
        $manager->flush();
    }
    /**
     * @param ObjectManager $manager
     * @param string $priorityType
     * @return bool
     */
    private function isPriorityExist(ObjectManager $manager, $priorityType)
    {
        return count($manager->getRepository('OroIssueBundle:IssuePriority')->find($priorityType)) > 0;
    }
}
