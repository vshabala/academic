<?php

namespace Oro\Bundle\IssueBundle\Controller\Api\REST;

use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations\NamePrefix;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SoapBundle\Controller\Api\Rest\RestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;

/**
 * @RouteResource("issue")
 * @NamePrefix("oro_api_")
 */
class IssueController extends RestController implements ClassResourceInterface
{
    /**
     * REST GET list
     *
     * @QueryParam(
     *      name="page",
     *      requirements="\d+",
     *      nullable=true,
     *      description="Page number, starting from 1. Defaults to 1."
     * )
     * @QueryParam(
     *      name="limit",
     *      requirements="\d+",
     *      nullable=true,
     *      description="Number of items per page. defaults to 10."
     * )
     * @ApiDoc(
     *      description="Get all issue items",
     *      resource=true
     * )
     * @AclAncestor("oro_issue_view")
     * @return Response
     */
    public function cgetAction()
    {
        $page  = (int)$this->get('request_stack')->getCurrentRequest()->get('page', 1);
        $limit = (int)$this->get('request_stack')->getCurrentRequest()->get('limit', self::ITEMS_PER_PAGE);
        $criteria = $this->getFilterCriteria($this->getSupportedQueryParameters('cgetAction'));
        return $this->handleGetListRequest($page, $limit, $criteria);
    }
    /**
     * REST GET item
     *
     * @param string $id
     *
     *@ApiDoc(
     *      description="Get issue item",
     *      resource=true
     * )
     * @AclAncestor("oro_issue_view")
     * @return Response
     */
    public function getAction($id)
    {
        return $this->handleGetRequest($id);
    }
    /**
     * REST PUT
     *
     * @param int $id Issue item id
     *
     * @ApiDoc(
     *      description="Update issue",
     *      resource=true
     * )
     * @AclAncestor("oro_issue_update")
     * @return Response
     */
    public function putAction($id)
    {
        return $this->handleUpdateRequest($id);
    }
    /**
     * Create new issue
     *
     * @ApiDoc(
     *      description="Create new issue",
     *      resource=true
     * )
     * @AclAncestor("oro_issue_create")
     * @return Response
     */
    public function postAction()
    {
        return $this->handleCreateRequest();
    }
    /**
     * REST DELETE
     *
     * @param int $id
     *
     * @ApiDoc(
     *      description="Delete issue",
     *      resource=true,
     *      requirements={
     *          {"name"="id", "dataType"="integer"},
     *      }
     * )
     * @Acl(
     *      id="oro_issue_delete",
     *      type="entity",
     *      class="OroIssueBundle:Issue",
     *      permission="DELETE"
     * )
     *   * @return Response
     */
    public function deleteAction($id)
    {
        return $this->handleDeleteRequest($id);
    }



    public function getManager()
    {
        return $this->get('oro_issue.manager.api');
    }

    /**
     * @return FormInterface
     */
    public function getForm()
    {
        return $this->get('oro_issue.form.issue_api');
    }
    /**
     * @return ApiFormHandler
     */
    public function getFormHandler()
    {
        return $this->get('oro_issue.form.handler.issue_api');
    }

    /**
     * {@inheritdoc}
     */
    protected function transformEntityField($field, &$value)
    {
        if (!$value) return;
        switch ($field) {
            case 'reporter':
            case 'assignee':
            case 'workflowItem':
            case 'parent':
                $value = $value->getId();
                break;
            case 'children':
                if (is_object($value)) {
                    $arr = [];
                        foreach ($value as $v) {
                            $arr[] = $v->getId();
                        }
                        $value = $arr;
                    } else {
                        $value = null;
                    }
                break;
            default:
                parent::transformEntityField($field, $value);
        }
    }
}
