<?php

namespace Oro\Bundle\IssueBundle\Controller;

use Oro\Bundle\IssueBundle\Entity\Issue;
use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class IssueController extends Controller
{
    /**
     * @Route("", name="issue_index")
     * @Template()
     * @Acl(
     *      id="oro_issue_view",
     *      type="entity",
     *      class="OroIssueBundle:Issue",
     *      permission="VIEW"
     * )
     */
    public function indexAction()
    {
        return array('gridName' => 'issue-grid');
        //return array('message' => "This is a new Issue Bundle");

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
    public function createAction(Request $request)
    {
        return $this->update(new Issue(), $request);
    }


    /**
     * @Route("/update", name="issue_update")
     * @Route("/update/{id}", name="issue_update", requirements={"id":"\d+"}, defaults={"id":0})
     * @Template()
     */
    public function updateAction(Issue $issue, Request $request)
    {
        return $this->update($issue, $request);
    }

    /**
     * @Route("/{id}", name="issue_view", requirements={"id"="\d+"})
     * @Template
     */
    public function viewAction(Issue $issue)
    {
        return array('issue' => $issue);
    }


    /**
     * @Route("/delete", name="issue_delete")
     * @Template()
     */
    public function deleteAction()
    {

    }


    private function update(Issue $issue, Request $request)
    {
        $form = $this->get('form.factory')->create('oro_issue_issue', $issue);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($issue);
            $entityManager->flush();

            return $this->get('oro_ui.router')->redirectAfterSave(
                array(
                    'route' => 'issue_index',
                   // 'parameters' => array('id' => $issue->getId()),
                ),
                array('route' => 'issue_index'),
                $issue
            );
        }

        return array(
            'entity' => $issue,
            'form' => $form->createView(),
        );
    }
}