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
     * @Route("/user/{userId}", name="oro_issue_user_issues", requirements={"userId"="\d+"})
     * @AclAncestor("oro_issue_view")
     * @Template("OroIssueBundle:Issue:widget/userIssues.html.twig")
     */
    public function userIssuesAction($userId)
    {
        return array('userId' => $userId);
        /** @var TaskRepository $repository */
        $repository = $this->getDoctrine()->getRepository('Oro\Bundle\IssueBundle\Entity\Issue');
        $issues = $repository->getIssuesOfUser($userId);
        return array('issues' => $issues);
    }



    /**
     * @Route(
     *      "/view/widget/{entityClass}/{entityId}",
     *      name="oro_issue_widget_issues"
     * )
     *
     * @AclAncestor("oro_issue_view")
     * @Template("OroIssueBundle:Issue:issues.html.twig")
     */
    public function widgetAction($entityClass, $entityId)
    {
        $entity = $this->getEntityRoutingHelper()->getEntity($entityClass, $entityId);

        return [
            'entity' => $entity
        ];

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
        $issue = new Issue();
        $issue->setReporter($this->getUser());
        $issue->setAssignee($this->getUser());

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
        
        return $this->update($issue, $request);
    }


    /**
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
        return $this->update($issue, $request);
    }

    /**
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

    /**
     * @return EntityRoutingHelper
     */
    protected function getEntityRoutingHelper()
    {
        return $this->get('oro_entity.routing_helper');
    }

}
