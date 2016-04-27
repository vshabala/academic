<?php
namespace Oro\Bundle\IssueBundle\Tests\Functional\Controller\Dashboard;

use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;

/**
 * Class DashboardControllerTest
 * @package Oro\Bundle\IssueBundle\Tests\Functional\Controller\Dashboard
 *
 * @dbIsolation
 * @dbReindex
 */
class DashboardControllerTest extends WebTestCase
{
    public function setUp()
    {
        $this->initClient(array());
    }

    public function testUserIssueAction()
    {
        $this->client->request(
            'GET',
            $this->getUrl('dashboard_issue')
        );
        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        $this->assertContains('Your Issues', $result->getContent());
    }
    
    public function testStatusChartAction()
    {
        $this->client->request(
            'GET',
            $this->getUrl('issue_status_chart')
        );
        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        $this->assertContains('Issues by Status', $result->getContent());
    }
}
