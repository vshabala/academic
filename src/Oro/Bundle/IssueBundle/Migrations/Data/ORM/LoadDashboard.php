<?php

namespace ORO\Bundle\IssueBundle\Migrations\Data\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Oro\Bundle\DashboardBundle\Migrations\Data\ORM\AbstractDashboardFixture;

class LoadDashboard extends AbstractDashboardFixture implements DependentFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function getDependencies()
    {
        return ['Oro\Bundle\DashboardBundle\Migrations\Data\ORM\LoadDashboardData'];
    }
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $mainDashboard = $this->findAdminDashboardModel($manager, 'main');
        if ($mainDashboard) {
            $mainDashboard
                ->addWidget($this->createWidgetModel('user_issue'))
                ->addWidget($this->createWidgetModel('issue_status'));
            $manager->flush();
        }
    }
}