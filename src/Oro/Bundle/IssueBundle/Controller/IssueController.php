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
        return [

            'entity_class' => $this->container->getParameter('oro_issue.issue.entity.class')
        ];
        
    }

    /**
     * @var int $userId
     *
     * @Route("/user/{userId}", name="oro_issue_user_issues", requirements={"userId"="\d+"})
     * @AclAncestor("oro_issue_view")
     * @Template("OroIssueBundle:Issue:widget/userIssues.html.twig")
     */
    public function userIssuesAction($userId)
    {
        return array('userId' => $userId);
    }

    /**
     * @return array
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
        $issue = new Issue();
        $issue->setReporter($this->getUser());

        $parentId = $request->query->getInt('parent');
        if ($parentId)
            $parent = $this
                ->getDoctrine()
                ->getRepository('OroIssueBundle:Issue')
                ->findOneBy(['id' => $parentId, 'issueType' => 'Story']);
        if (isset($parent)){
            $issue->setParent($parent);
            $issue->setIssueType('Subtask');
        }

        $userId = $request->query->getInt('userid');
        if ($userId)
            $user = $this
                ->getDoctrine()
                ->getRepository('OroUserBundle:User')
                ->findOneBy(['id' => $userId]);
        if (isset($user)){
            $issue->setAssignee($user);
        }
        else {
            $issue->setAssignee($this->getUser());
        }

        $formAction = $this->get('oro_entity.routing_helper')
            ->generateUrlByRequest('issue_create', $this->getRequest());

        return $this->update($issue, $formAction);

    }

    /**
     * @param Issue $issue
     * @return array
     * @Route("/update/{id}", name="issue_update", requirements={"id":"\d+"}, defaults={"id":0})
     * @Acl(
     *      id="oro_issue_update",
     *      type="entity",
     *      class="OroIssueBundle:Issue",
     *      permission="EDIT"
     * )
     * @Template()
     */
    public function updateAction(Issue $issue, Request $request)
    {
        $formAction = $this->get('router')->generate('issue_update', ['id' => $issue->getId()]);
        return $this->update($issue, $formAction);
    }

    /**
     * @param Issue $issue
     * @return array
     *
     * @Route("/{id}", name="issue_view", requirements={"id"="\d+"})
     * @Acl(
     *      id="oro_issue_view",
     *      type="entity",
     *      class="OroIssueBundle:Issue",
     *      permission="VIEW"
     * )
     * @Template
     */
    public function viewAction(Issue $issue)
    {
        return array('entity' => $issue);
    }

    /**
     * @param Issue $issue
     * @param $formAction
     * @return array
     */
    private function update(Issue $issue, $formAction)
    {
        $saved = false;
        if ($this->get('oro_issue.form.handler.issue')->process($issue)) {
            if (!$this->get('request_stack')->getCurrentRequest()->get('_widgetContainer')) {
                $this->get('session')->getFlashBag()->add(
                    'success',
                    $this->get('translator')->trans('oro.issue.saved_message')
                );
                return $this->get('oro_ui.router')->redirectAfterSave(
                    array(
                        'route' => 'issue_update',
                        'parameters' => array('id' => $issue->getId()),
                    ),
                    array(
                        'route' => 'issue_view',
                        'parameters' => array('id' => $issue->getId()),
                    )
                );
            }
            $saved = true;
        }
        return array(
            'entity'     => $issue,
            'saved'      => $saved,
            'form'       => $this->get('oro_issue.form.handler.issue')->getForm()->createView(),
            'formAction' => $formAction,
        );
    }

    /**
     * @return EntityRoutingHelper
     */
    protected function getEntityRoutingHelper()
    {
        return $this->get('oro_entity.routing_helper');
    }

}
