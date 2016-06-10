<?php
namespace Oro\Bundle\IssueBundle\Migrations\Data\Demo\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Oro\Bundle\OrganizationBundle\Entity\Organization;
use Oro\Bundle\UserBundle\Entity\User;
use Oro\Bundle\UserBundle\Entity\Role;
use Doctrine\ORM\EntityManager;
use Oro\Bundle\UserBundle\Migrations\Data\ORM\LoadRolesData;

class LoadUsers extends AbstractFixture implements ContainerAwareInterface
{
    const FLUSH_MAX = 10;
    /** @var ContainerInterface */
    protected $container;

    /**
     * Load users
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $firstNames = ['Jerry', 'Harry', 'Frank', 'Anna', 'Victor', 'Mike', 'David'];
        $lastNames = ['Smith', 'Brown', 'Wilson', 'Miller', 'Taylor', 'Clark', 'Lee'];
        $organization = $manager->getRepository('OroOrganizationBundle:Organization')->getFirst();
        $businessUnit = $manager->getRepository('OroOrganizationBundle:BusinessUnit')->getFirst();
        $roles = $manager->getRepository('OroUserBundle:Role')->findAll();

        for ($i = 0; $i < 7; $i++) {
            $firstName = $firstNames[$i];
            $lastName = $lastNames[$i];
            $username = strtolower($firstName . $lastName). mt_rand(100, 999);
            $email = $lastName .rand(10, 99) . '@' . 'gmail.com';

            $userManager = $this->container->get('oro_user.manager');
            $user = $userManager->createUser();

            $user->setEmail($email);
            $user->setUsername($username);
            $user->setFirstName($firstName);
            $user->setLastName($lastName);
            $user->setOwner($businessUnit);
            $user->addBusinessUnit($businessUnit);
            $user->addRole($roles[mt_rand(0, count($roles)-1)]);
            $user->setOrganization($organization);
            $user->addOrganization($organization);
            // very week password - just for demo purposes
            $user->setPlainPassword($firstName);
            $userManager->updatePassword($user);
            $manager->persist($user);
        }
        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
