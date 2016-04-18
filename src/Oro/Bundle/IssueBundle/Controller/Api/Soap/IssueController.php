<?php

namespace Oro\Bundle\IssueBundle\Controller\Api\Soap;

use Symfony\Component\Form\FormInterface;
use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Oro\Bundle\SoapBundle\Entity\Manager\ApiEntityManager;
use Oro\Bundle\SoapBundle\Controller\Api\Soap\SoapController;
use Oro\Bundle\SoapBundle\Form\Handler\ApiFormHandler;
use FOS\RestBundle\Controller\Annotations\NamePrefix;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class IssueController extends SoapController
{
    /**
     * @Soap\Method("getIssues")
     * @Soap\Param("page", phpType="int")
     * @Soap\Param("limit", phpType="int")
     * @Soap\Result(phpType = "Oro\Bundle\IssueBundle\Entity\IssueSoap[]")
     * @AclAncestor("oro_issue_view")
     */
    public function cgetAction($page = 1, $limit = 10)
    {
        return $this->handleGetListRequest($page, $limit);
    }
    /**
     * @Soap\Method("getIssue")
     * @Soap\Param("id", phpType = "int")
     * @Soap\Result(phpType = "Oro\Bundle\IssueBundle\Entity\IssueSoap")
     * @AclAncestor("oro_issue_view")
     */
    public function getAction($id)
    {
        return $this->handleGetRequest($id);
    }
    /**
     * @Soap\Method("createIssue")
     * @Soap\Param("issue", phpType = "Oro\Bundle\IssueBundle\Entity\IssueSoap")
     * @Soap\Result(phpType = "int")
     * @AclAncestor("oro_issue_create")
     */
    public function createAction($issue)
    {
        return $this->handleCreateRequest();
    }
    /**
     * @Soap\Method("updateIssue")
     * @Soap\Param("id", phpType = "int")
     * @Soap\Param("issue", phpType = "Oro\Bundle\IssueBundle\Entity\IssueSoap")
     * @Soap\Result(phpType = "boolean")
     * @AclAncestor("oro_issue_update")
     */
    public function updateAction($id, $issue)
    {
        return $this->handleUpdateRequest($id);
    }
    /**
     * @Soap\Method("deleteIssue")
     * @Soap\Param("id", phpType = "int")
     * @Soap\Result(phpType = "boolean")
     * @AclAncestor("oro_issue_delete")
     */
    public function deleteAction($id)
    {
        return $this->handleDeleteRequest($id);
        
    }

    /**
     * @return ApiEntityManager
     */
    public function getManager()
    {
        return $this->container->get('oro_issue.manager.api');
    }
    /**
     * @return FormInterface
     */
    public function getForm()
    {
        return $this->container->get('oro_issue.form.issue_api.soap');
    }
    /**
     * @return ApiFormHandler
     */
    public function getFormHandler()
    {
        return $this->container->get('oro_issue.form.handler.issue_api.soap');
    }
    /**
     * {@inheritDoc}
     */
    protected function fixFormData(array &$data, $entity)
    {
        parent::fixFormData($data, $entity);
        unset($data['id']);
        unset($data['updatedAt']);
        return true;
    }
}
