<?php
namespace Oro\Bundle\IssueBundle\Migrations\Data\Demo\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Oro\Bundle\IssueBundle\OroIssueBundle;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Oro\Bundle\IssueBundle\Entity\Issue;
use Oro\Bundle\IssueBundle\Entity\IssuePriority;
use Oro\Bundle\WorkflowBundle\Entity\WorkflowStep;
use Oro\Bundle\TagBundle\Entity\Tag;
use Oro\Bundle\TagBundle\Entity\Tagging;
use Oro\Bundle\OrganizationBundle\Entity\Organization;
use Oro\Bundle\UserBundle\Entity\User;
use Oro\Bundle\NoteBundle\Entity\Note;
use Doctrine\ORM\EntityManager;

class LoadIssues extends AbstractFixture implements DependentFixtureInterface, ContainerAwareInterface
{
    const FLUSH_MAX = 10;
    /**
     * @var array
     */
    protected $data = array(
        array(
            'code' => 'AA-0001',
            'summary' => 'Issue AA-0001 - no notes',
            'type' => 'Bug',
            'description' => 'This is description of the AA-0001 issue',
            'notes' => 0,
        ),
        array(
            'code' => 'BB-0001',
            'summary' => 'Issue BB-0001 - 3 notes',
            'type' => 'Task',
            'description' => 'This is description of the BB-0001 issue',
            'notes' => 3,
        ),
        array(
            'code' => 'AA-0002',
            'summary' => 'Issue AA-0002 - no notes',
            'type' => 'Story',
            'description' => 'This is description of the AA-0002 issue',
            'notes' => 0,
        ),
        array(
            'code' => 'CC-0001',
            'summary' => 'Story with 2 subtasks',
            'type' => 'Story',
            'description' => 'This is description of CC-0001issue',
            'notes' => 0,
        ),
        array(
            'code' => 'CC-0001/1',
            'summary' => 'Subtask 1 for CC-0001',
            'type' => 'Subtask',
            'description' => 'This is description of CC-0001/1 issue',
            'notes' => 0,
            'parent' => 'CC-0001'

        ),
        array(
            'code' => 'CC-0001/2',
            'summary' => 'Subtask 2 for CC-0001',
            'type' => 'Subtask',
            'description' => 'This is description of CC-0001/2 issue',
            'notes' => 2,
            'parent' => 'CC-0001'
        ),
    );

    /**
     * @var array
     */
    protected $tags = array('apple','banana','orange');
    /**
     * @var ContainerInterface
     */
    protected $container;


    /**
     * {@inheritdoc}
     */
    public function getDependencies()
    {
        return [
            'Oro\Bundle\IssueBundle\Migrations\Data\Demo\ORM\LoadUsers',
        ];
    }

    protected function doubleData()
    {
        $total = count($this->data);
        $fieldsToModify = ['code', 'summary', 'description', 'parent'];
        for ($i=0; $i < $total; $i++) {
            $item = $this->data[$i];
            foreach ($fieldsToModify as $field) {
                $item[$field] = str_replace('000', '100', $item[$field]);
            }
            $this->data[] = $item;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->DoubleData();
        $organization = $manager->getRepository('OroOrganizationBundle:Organization')->getFirst();
        $users = $manager->getRepository('OroUserBundle:User')->findAll();
        $priorities = $manager->getRepository('OroIssueBundle:IssuePriority')->findAll();
        $workflowSteps = $manager->getRepository('OroWorkflowBundle:WorkflowStep')->findAll();
        $addedIssues = [];

        foreach ($this->data as $item) {
            $issue = new Issue();
            $issue->setSummary($item['summary']);
            $issue->setCode($item['code']);
            $issue->setDescription($item['description']);
            $issue->setAssignee($this->getRandomEntity($users));
            $issue->setReporter($this->getRandomEntity($users));
            $issue->setIssueType($item['type']);
            $issue->setIssuePriority($this->getRandomEntity($priorities));
            $issue->setOrganization($organization);
            $issue->setWorkflowStep($this->getRandomEntity($workflowSteps));

            if ($item['notes']) {
                for ($i = 0; $i < $item['notes']; $i++) {
                    $note = new Note();
                    $note->setMessage('This is note message '.$i)
                        ->setOwner($this->getRandomEntity($users))
                        ->setOrganization($organization)
                        ->setTarget($issue);
                    $manager->persist($note);
                }
            }

            if (isset($item['parent'])) {
                $parentIssue = $manager ->getRepository('OroIssueBundle:Issue')
                    ->findOneBy(['code' => $item['parent']]);
                if ($parentIssue) {
                    $issue->setParent($parentIssue);
                }
            }

            if ($item['code'] == 'AA-0002') {
                $issue->addRelatedIssue($addedIssues[0]);
                $issue->addRelatedIssue($addedIssues[1]);
            }

            $manager->persist($issue);
            $addedIssues[] = $issue;
        }
        $manager->flush();

        //Adding tags
        $tagNames = ['banana','apple','orange'];
        $issues = $manager->getRepository('OroIssueBundle:Issue')->findAll();
        foreach ($tagNames as $name) {
            $tag = new Tag();
            $tag->setName($name)
                ->setOwner($this->getRandomEntity($users))
                ->setOrganization($organization);
            $manager->persist($tag);

            $total = mt_rand(1, 2);
            for ($i = 0; $i < $total; $i++) {
                $tagging = new Tagging();
                $tagging->setEntity($this->getRandomEntity($issues));
                $tagging->setTag($tag);
                $tagging->setOwner($this->getRandomEntity($users));
                $manager->persist($tagging);
            }
        }

        $manager->flush();

    }

    /**
     * @param object[] $entities
     *
     * @return object|null
     */
    protected function getRandomEntity($entities)
    {
        if (empty($entities)) {
            return null;
        }
        return $entities[rand(0, count($entities) - 1)];
    }

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
