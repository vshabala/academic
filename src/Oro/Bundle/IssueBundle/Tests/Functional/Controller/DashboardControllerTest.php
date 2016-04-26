<?php
namespace Oro\Bundle\IssueBundle\Tests\Functional\Controller\Dashboard;

use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;

/**
 * Class DashboardControllerTest
 * @package OroAcademic\Bundle\IssueBundle\Tests\Functional\Controller\Dashboard
 *
 * @dbIsolation
 */
class DashboardControllerTest extends WebTestCase
{
    public function setUp()
    {
        $this->initClient(array(), $this->generateBasicAuthHeader());
    }
    public function testIssuesByStatusAction()
    {
        $this->client->request(
            'GET',
            $this->getUrl(
                'issue_status_chart',[],true
            )
        );
        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        $this->assertContains('Issues by Status', $result->getContent());
    }
    public function testIssueWidgetGridAction()
    {
        $this->client->request(
            'GET',
            $this->getUrl(
                'dashboard_issue', [], true
            )
        );
        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        $this->assertContains('Your Issues', $result->getContent());
    }
}