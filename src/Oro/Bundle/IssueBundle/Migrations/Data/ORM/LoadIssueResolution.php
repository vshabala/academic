<?php

namespace Oro\Bundle\IssueBundle\Migrations\Data\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Oro\Bundle\IssueBundle\Entity\IssueResolution;

class LoadIssueResolution extends AbstractFixture
{
    /**
     * @var array
     */
    protected $data = array(
        array(
            'label' => 'Fixed',
            'name' => 'fixed',
            'order' => 1,
        ),
        array(
            'label' => 'Cannot Reproduce',
            'name' => 'cannot_reproduce',
            'order' => 2,
        ),
        array(
            'label' => 'Duplicate',
            'name' => 'duplicate',
            'order' => 3,
        ),
        array(
            'label' => "Won't Fix",
            'name' => 'wont_fix',
            'order' => 4,
        ),
        array(
            'label' => 'Works as designed',
            'name' => 'works_as_designed',
            'order' => 5,
        ),
    );
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->data as $resolution) {
            if (!$this->isResolutionExist($manager, $resolution['name'])) {
                $entity = new IssueResolution($resolution['name']);
                $entity->setLabel($resolution['label']);
                $entity->setOrder($resolution['order']);
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
    private function isResolutionExist(ObjectManager $manager, $resolutionType)
    {
        return count($manager->getRepository('OroIssueBundle:IssueResolution')->find($resolutionType)) > 0;
    }
}