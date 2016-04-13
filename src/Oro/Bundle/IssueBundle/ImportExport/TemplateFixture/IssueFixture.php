<?php
namespace Oro\Bundle\IssueBundle\ImportExport\TemplateFixture;

use Oro\Bundle\ImportExportBundle\TemplateFixture\AbstractTemplateRepository;
use Oro\Bundle\ImportExportBundle\TemplateFixture\TemplateFixtureInterface;
use Oro\Bundle\IssueBundle\Entity\Issue;
use Oro\Bundle\IssueBundle\Entity\IssuePriority;

class IssueFixture extends AbstractTemplateRepository implements TemplateFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function getEntityClass()
    {
        return 'Oro\Bundle\IssueBundle\Entity\Issue';
    }
    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->getEntityData('AA-0001');
    }
    /**
     * {@inheritdoc}
     */
    protected function createEntity($key)
    {
        return new Issue();
    }
    /**
     * @param string $key
     * @param Issue   $entity
     */
    public function fillEntityData($key, $entity)
    {
        $userRepo         = $this->templateManager
            ->getEntityRepository('Oro\Bundle\UserBundle\Entity\User');
        $priorityRepo     = $this->templateManager
            ->getEntityRepository('Oro\Bundle\IssueBundle\Entity\IssuePriority');
        $organizationRepo = $this->templateManager
            ->getEntityRepository('Oro\Bundle\OrganizationBundle\Entity\Organization');

        switch ($key) {
            case 'AA-0001':
                $entity->setCode('AA-0001');
                $entity->setSummary('Example Issue');
                $entity->setDescription('Example Issue Description');
                $entity->setType('Task');
                //$entity->setParent('Task');
                //$entity->setPriority(new IssuePriority('normal'));
                $entity->setReporter($userRepo->getEntity('test test'));
                $entity->setAssignee($userRepo->getEntity('test test'));
                //$entity->setOwner($userRepo->getEntity('test test'));
                $entity->setOrganization($organizationRepo->getEntity('default'));
                $entity->setCreatedAt(new \DateTime());
                $entity->setUpdatedAt(new \DateTime());
                return;
        }
        parent::fillEntityData($key, $entity);
    }
}