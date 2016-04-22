<?php

namespace Oro\Bundle\IssueBundle\Entity;

use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Oro\Bundle\SoapBundle\Entity\SoapEntityInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Soap\Alias("Oro.Bundle.IssueBundle.Entity.Issue")
 */
class IssueSoap extends Issue implements SoapEntityInterface
{
    /**
     * @Soap\ComplexType("int", nillable=true)
     */
    protected $id;

    /**
     * @Soap\ComplexType("string", nillable=false)
     */
    protected $code;

    /**
     * @Soap\ComplexType("string", nillable=false)
     */
    protected $summary;

    /**
     * @Soap\ComplexType("string", nillable=false)
     */
    protected $description;

    /**
     * @Soap\ComplexType("string", nillable=true)
     */
    protected $issueType;

    /**
     * @Soap\ComplexType("string", nillable=true)
     */
    protected $issuePriority;

    /**
     * @Soap\ComplexType("string", nillable=true)
     */
    protected $issueResolution;
    /**
     * @Soap\ComplexType("int", nillable=true)
     */
    protected $reporter;

    /**
     * @Soap\ComplexType("int", nillable=true)
     */
    protected $assignee;

    /**
     * @Soap\ComplexType("dateTime", nillable=true)
     */
    protected $createdAt;

    /**
     * @Soap\ComplexType("dateTime", nillable=true)
     */
    protected $updatedAt;

    /**
     * @Soap\ComplexType("int", nillable=true)
     */
    protected $workflowItem;

    /**
     * @Soap\ComplexType("int[]", nillable=true)
     */
    protected $workflowStep;

    /**
     * @Soap\ComplexType("int", nillable=true)
     */
    protected $parent;

    /**
     * @Soap\ComplexType("int[]", nillable=true)
     */
    protected $children;

   /**
    * @Soap\ComplexType("int[]", nillable=true)
    */
    protected $relatedIssues;

    /**
     * @param Issue $issue
     */
    public function soapInit($issue)
    {
        $this->id = $issue->id;
        $this->code = $issue->message;
        $this->summary = $issue->summary;
        $this->description = $issue->description;
        $this->issueType = $issue->issueType;
        $this->issuePriority = $issue->issuePriority ? $issue->issuePriority->getName() : null;
        $this->issueResolution = $issue->issueResolution ? $issue->issueResolution->getName() : null;
        $issue->reporter = $this->getEntityId($issue->reporter);
        $issue->assignee = $this->getEntityId($issue->assignee);
        $this->createdAt = $issue->createdAt;
        $this->updatedAt = $issue->updatedAt;
        $issue->workflowItem = $this->getEntityId($issue->workflowItem);
        $issue->workflowStep = $this->getEntityId($issue->workflowStep);
        $issue->parent = $this->getEntityId($issue->parent);
        $issue->children = $this->cgetEntityId($issue->children);
       // $issue->relatedIssues = $this->cgetEntityId($issue->relatedIssues);

    }

    /**
     * @param object $entity
     *
     * @return integer|null
     */
    protected function getEntityId($entity)
    {
        if ($entity) {
            return $entity->getId();
        }
        return null;
    }

    /**
     * @param ArrayCollection $entities
     *
     * @return array|null
     */
    protected function cgetEntityId($entities)
    {
        if ($entities) {
            $ids = [];
            foreach ($entities as $entity) {
                $ids[] = $entity->getId();
            }
            return $ids;
        }
        return null;
    }
}
