<?php

namespace Oro\Bundle\IssueBundle\Tests\Functional\Entity\Repository;

use Doctrine\ORM\EntityManager;
use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;

/**
 * @dbIsolation
 */
class IssueRepositoryTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->initClient([], $this->generateBasicAuthHeader());
       
        $this->loadFixtures(
            [
                'Oro\Bundle\IssueBundle\Migrations\Data\Demo\ORM\LoadIssues',
            ]
        );

        $this->em = $this->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testCountIssuesPerStatus()
    {
        $statuses = $this->em
            ->getRepository('OroIssueBundle:Issue')->countIssuesPerStatus();

        foreach ($statuses as $status) {
            $this->assertArrayHasKey('label', $status);
            $this->assertArrayHasKey('number', $status);
            if ($status['label'] == 'Open') {
                $cnt = 12;
            } else {
                $cnt = 0;
            }
            $this->assertEquals($cnt, $status['number']);
        }
    }
}
