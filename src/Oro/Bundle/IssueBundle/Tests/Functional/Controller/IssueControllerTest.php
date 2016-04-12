<?php

namespace Oro\Bundle\IssueBundle\Tests\Functional\Controller;
use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;

/**
 * @outputBuffering enabled
 * @dbIsolation
 * @dbReindex
 */
class IssueControllersTest extends WebTestCase
{
    protected function setUp()
    {
        $this->initClient(array(), $this->generateBasicAuthHeader());
    }

    public function testCreate()
    {
        $crawler = $this->client->request('GET', $this->getUrl('issue_create'));
        $form = $crawler->selectButton('Save')->form();
        $form['oro_issue_issue_form[summary]'] = 'New Issue';
        $form['oro_issue_issue_form[code]'] = '0001';
        $form['oro_issue_issue_form[description]'] = 'New description';
        $form['oro_issue_issue_form[issueType]'] = 'Bug';
        $form['oro_issue_issue_form[issuePriority]'] = 'low';
        $form['oro_issue_issue_form[reporter]'] = '1';
        $form['oro_issue_issue_form[assignee]'] = '1';

        $this->client->followRedirects(true);
        $crawler = $this->client->submit($form);
        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        //$this->assertContains("The issue is saved", $crawler->html());
    }
    /**
     * @depends testCreate
     */
    public function testUpdate()
    {
        $response = $this->client->requestGrid(
            'issue-grid'
            //array('issue-grid[_filter][ownerName][value]' => 'John Doe')
        );
        $result = $this->getJsonResponseContent($response, 200);
        $result = reset($result['data']);

        $crawler = $this->client->request(
            'GET',
            $this->getUrl('issue_update', array('id' => $result['id']))
        );
        $form = $crawler->selectButton('Save')->form();

        $form['oro_issue_issue_form[summary]'] = 'test of issue update';
        $form['oro_issue_issue_form[description]'] = 'Description updated';

        $this->client->followRedirects(true);
        $crawler = $this->client->submit($form);
        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        $this->assertContains("The issue is saved", $crawler->html());
    }
    /**
     * @depends testUpdate
     */
    public function testView()
    {
        $response = $this->client->requestGrid(
            'issue-grid'
            //array('issue-grid[_filter][ownerName][value]' => 'John Doe')
        );
        $result = $this->getJsonResponseContent($response, 200);
        $result = reset($result['data']);
        $this->client->request(
            'GET',
            $this->getUrl('issue_view', array('id' => $result['id']))
        );
        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);

        $this->assertContains('test of issue update', $result->getContent());

    }
    /**
     * @depends testUpdate
     */
    public function testIndex()
    {
        $this->client->request('GET', $this->getUrl('issue_index'));
        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);

        $this->assertContains('test of issue update', $result->getContent());

    }
}
