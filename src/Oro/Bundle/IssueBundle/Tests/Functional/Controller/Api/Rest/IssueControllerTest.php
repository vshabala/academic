<?php

namespace Oro\Bundle\IssueBundle\Tests\Functional\Controller\Api\REST;

use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;

/**
 * @outputBuffering enabled
 * @dbIsolation
 */
class IssueControllerTest extends WebTestCase
{
    /**
     * @var array
     */
    protected $issue = [
        'code' => 'ZZ-0001',
        'summary' => 'New Rest Issue',
        'description' => 'New Rest Issue description',
        'issueType' => 'Task',
        'issuePriority' => 'low',
        'reporter' => 1,
        'assignee' => 1,
    ];

    /**
     * @var int
     */
    protected $issueTotal;

    protected function setUp()
    {
        $this->initClient([], $this->generateWsseAuthHeader());
        $this->client->request('GET', $this->getUrl('oro_api_get_issues'));
        $issues = $this->getJsonResponseContent($this->client->getResponse(), 200);
        $this->issueTotal = count($issues);
    }

    public function testCreate()
    {
        $this->client->request('POST', $this->getUrl('oro_api_post_issue'), ['issue' => $this->issue]);
        $issue = $this->getJsonResponseContent($this->client->getResponse(), 201);
        return $issue['id'];
    }

    /**
     * @depends testCreate
     */
    public function testCget()
    {
        $this->client->request('GET', $this->getUrl('oro_api_get_issues'));
        $issues = $this->getJsonResponseContent($this->client->getResponse(), 200);

        $this->assertCount($this->issueTotal++, $issues);
    }

    /**
     * @depends testCreate
     *
     * @param integer $id
     */
    public function testGet($id)
    {
        $this->client->request('GET', $this->getUrl('oro_api_get_issue', ['id' => $id]));
        $issue = $this->getJsonResponseContent($this->client->getResponse(), 200);

        $this->assertEquals($this->issue['code'], $issue['code']);
    }

    /**
     * @depends testCreate
     *
     * @param integer $id
     */
    public function testPut($id)
    {
        $updatedIssue = array_merge($this->issue, ['summary' => 'Updated summary']);
        $this
            ->client
            ->request('PUT', $this->getUrl('oro_api_put_issue', ['id' => $id]), ['issue' => $updatedIssue]);
        $result = $this->client->getResponse();
        $this->assertEmptyResponseStatusCodeEquals($result, 204);

        $this->client->request('GET', $this->getUrl('oro_api_get_issue', ['id' => $id]));

        $issue = $this->getJsonResponseContent($this->client->getResponse(), 200);

        $this->assertEquals($issue['summary'], $updatedIssue['summary']);
    }

    /**
     * @depends testCreate
     *
     * @param integer $id
     */
    public function testDelete($id)
    {
        $this->client->request('DELETE', $this->getUrl('oro_api_delete_issue', ['id' => $id]));
        $result = $this->client->getResponse();
        $this->assertEmptyResponseStatusCodeEquals($result, 204);

        $this->client->request('GET', $this->getUrl('oro_api_get_issue', ['id' => $id]));
        $result = $this->client->getResponse();
        $this->assertJsonResponseStatusCodeEquals($result, 404);
    }
}
