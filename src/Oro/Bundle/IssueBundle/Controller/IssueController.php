<?php

namespace Oro\Bundle\IssueBundle\Controller;

use Oro\Bundle\IssueBundle\Entity\Issue;
use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class IssueController extends Controller
{
    /**
     * @Route("", name="issue_index")
     * @Template()
     */
    public function indexAction()
    {
        return array('message' => "This is a new Issue Bundle");
    }


    /**
     * @Route("/create", name="issue_create")
     * @Acl(
     *      id="oro_issue_create",
     *      type="entity",
     *      class="OroIssueBundle:Issue",
     *      permission="CREATE"
     * )
     * @Template("OroIssueBundle:Issue:update.html.twig")
     */
    public function createAction()
    {
        return $this->update(new Issue());
    }

    /**
     * @Route("/create",  name="issue_create1")
     * @Template("OroIssueBundle:Default:update.html.twig")
     */
    public function createActionMy()
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

    /**
     * @param Issue $entity
     * @return array|RedirectResponse
     */
    protected function update(Issue $entity)
    {
        if ($this->get('oro_issue.form.handler.issue')->process($entity)) {
            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('oro.issue.controller.issue.saved.message')
            );

            return $this->redirect($this->generateUrl('issue_link'));
        }

        return array(
            'entity' => $entity,
            'form' => $this->get('oro_issue.form.issue')->createView(),
        );
    }
}