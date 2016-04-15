<?php
namespace Oro\Bundle\IssueBundle\Migrations\Data\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Oro\Bundle\IssueBundle\Entity\Issue;
use Oro\Bundle\IssueBundle\Entity\IssuePriority;
use Oro\Bundle\OrganizationBundle\Entity\Organization;
use Oro\Bundle\UserBundle\Entity\User;
use Oro\Bundle\NoteBundle\Entity\Note;
use Doctrine\ORM\EntityManager;
class LoadIssues extends AbstractFixture implements DependentFixtureInterface, ContainerAwareInterface
{
    /**
     * @var array
     */
    protected $data = array(
        array(
            'code' => 'AA-0001',
            'summary' => 'First Issue',
            'type' => 'Bug',
            'description' => 'This is description of the first issue',
            'notes' => 2,
            'related' => 0
        ),
        array(
            'code' => 'BB-0001',
            'summary' => 'Second Issue',
            'type' => 'Task',
            'description' => 'This is description of the second issue',
            'notes' => 3,
            'related' => 1
        ),
        array(
            'code' => 'AA-0002',
            'summary' => 'Third Issue',
            'type' => 'Story',
            'description' => 'This is description of the second issue',
            'notes' => 2,
            'related' => 2
        ),
    );
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
            'Oro\Bundle\IssueBundle\Migrations\Data\ORM\LoadUsers',
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $organization = $manager->getRepository('OroOrganizationBundle:Organization')->getFirst();
        $users = $manager->getRepository('OroUserBundle:User')->findAll();
        $priorities = $manager->getRepository('OroIssueBundle:IssuePriority')->findAll();

        foreach ($this->data as $data) {
            $issue = new Issue();
            $issue->setSummary($data['summary']);
            $issue->setCode($data['code']);
            $issue->setDescription($data['description']);
            $issue->setAssignee($this->getRandomEntity($users));
            $issue->setReporter($this->getRandomEntity($users));
            $issue->setIssueType($data['type']);
            $issue->setIssuePriority($this->getRandomEntity($priorities));
            $issue->setOrganization($organization);
            $manager->persist($issue);
        }
        $manager->flush();

    }
    /**
     * Load users
     *
     * @return void
     */
    public function loadIssues()
    {
        foreach ($this->data as $data) {
            $existIssues = $this->em->getRepository('OroIssueBundle:Issue')->findAll();
            $issue = new Issue();

            $issue->setSummary($data['summary']);
            $issue->setCode($data['code']);
            $issue->setDescription($data['summary']);
            $issue->setAssignee($this->getRandomEntity($this->users));
            $issue->setReporter($this->getRandomEntity($this->users));
            $issue->setType($data['type']);
            $issue->setPriority($this->getRandomEntity($this->priorities));
            $issue->setOrganization($this->organization);
            if ($data['type'] == 'Subtask' && $data['parent']) {
                $parent = $this->em
                    ->getRepository('OroIssueBundle:Issue')
                    ->findOneBy(['code' => $data['parent']]);
                $issue->setParent($parent);
            }
            for ($i = 1; $i <= $data['related']; $i++) {
                if ($existIssues) {
                    $issue->addRelatedIssue($this->getRandomEntity($existIssues));
                }
            }
            $this->persist($issue);
            for ($i = 1; $i <= $data['notes']; $i++) {
                $note = new Note();
                $note->setMessage('Note #'.$i)
                    ->setOrganization($this->organization)
                    ->setOwner($this->getRandomEntity($this->users))
                    ->setTarget($issue);
                $this->persist($note);
            }
            $this->flush();
        }
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

}
