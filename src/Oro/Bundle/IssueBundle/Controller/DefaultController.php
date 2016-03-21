<?php

namespace Oro\Bundle\IssueBundle\Controller;

use Oro\Bundle\IssueBundle\Entity\Issue;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("", name="issue_link")
     * @Template()
     */
    public function indexAction()
    {
        return array('message' => "This is a new Issue Bundle");
    }

    /**
     * @Route("/create",  name="issue_create")
     * @Template("OroIssueBundle:Default:update.html.twig")
     */
    public function createAction()
    {
        $issue = new Issue();

        $form = $this->createFormBuilder($issue)
            ->add('code', 'text')
            ->add('summary', 'textarea')
            ->add('description', 'textarea')
            ->add('save', 'submit', array('label' => 'Create Issue'))
            ->getForm();

        return $this->render('OroIssueBundle:Default:update.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/update", name="issue_update")
     * @Template()
     */
    public function updateAction()
    {

    }


    /**
     * @Route("/delete", name="issue_delete")
     * @Template()
     */
    public function deleteAction()
    {

    }
}