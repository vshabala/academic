<?php

namespace Oro\Bundle\IssueBundle\Entity;


use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Oro\Bundle\OrganizationBundle\Entity\Organization;
use Doctrine\ORM\Mapping as ORM;

use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;
use Oro\Bundle\IssueBundle\Model\ExtendIssue;
use Oro\Bundle\UserBundle\Entity\User;
use Oro\Bundle\EntityBundle\EntityProperty\DatesAwareInterface;
use Oro\Bundle\EntityBundle\EntityProperty\DatesAwareTrait;
use Oro\Bundle\WorkflowBundle\Entity\WorkflowItem;
use Oro\Bundle\WorkflowBundle\Entity\WorkflowStep;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Oro\Bundle\IssueBundle\Entity\Repository\IssueRepository")
 * @ORM\Table(name="oro_issue")
 * @Config(
 *     defaultValues={
 *      "workflow"={
 *          "active_workflow"="issue_flow"
 *      },
 *      "ownership"={
 *              "owner_type"="USER",
 *              "owner_field_name"="reporter",
 *              "owner_column_name"="user_reporter_id",
 *              "organization_field_name"="organization",
 *              "organization_column_name"="organization_id"
 *          },
 *      "dataaudit"={
 *              "auditable"=true
 *          },
 *      "tag"={
 *              "enabled"=true
 *          },
 *       "security"={
 *              "type"="ACL",
 *              "group_name"=""
 *          },
 *     }
 *     )
 *
 */
class Issue extends ExtendIssue implements DatesAwareInterface
{
    use DatesAwareTrait;


    const ENTITY_NAME = 'Oro\Bundle\IssueBundle\Entity\Issue';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $code
     *
     * @ORM\Column(name="code", type="string", length=50, nullable=false)
     */
    protected $code;

    /**
     * @var Organization
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\OrganizationBundle\Entity\Organization")
     * @ORM\JoinColumn(name="organization_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $organization;

    /**
     * @var string $summary
     *
     * @ORM\Column(name="summary", type="text", nullable=false)
     */
    protected $summary;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     * @var reporter
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_reporter_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $reporter;

    /**
     * @var assignee
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_assignee_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $assignee;

    /**
     * @var WorkflowItem
     *
     * @ORM\OneToOne(targetEntity="Oro\Bundle\WorkflowBundle\Entity\WorkflowItem")
     * @ORM\JoinColumn(name="workflow_item_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $workflowItem;

    /**
     * @var WorkflowStep
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\WorkflowBundle\Entity\WorkflowStep")
     * @ORM\JoinColumn(name="workflow_step_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $workflowStep;

    /**
     * @var string
     *
     * @ORM\Column(name="issue_type", type="string", length=32, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Regex("/^(Bug|Task|Subtask|Story)/")
     * @ConfigField(
     *      defaultValues={
     *          "dataaudit"={
     *              "auditable"=true
     *          }
     *      }
     * )
     */
    protected $issueType;

    /**
     * @var IssuePriority
     *
     * @ORM\ManyToOne(targetEntity="IssuePriority")
     * @ORM\JoinColumn(name="issue_priority_name", referencedColumnName="name", onDelete="SET NULL")
     * @ConfigField(
     *      defaultValues={
     *          "dataaudit"={
     *              "auditable"=true
     *          }
     *      }
     * )
     */
    protected $issuePriority;

    /**
     * @var IssueResolution
     *
     * @ORM\ManyToOne(targetEntity="IssueResolution")
     * @ORM\JoinColumn(name="issue_resolution_name", referencedColumnName="name", onDelete="SET NULL")
     * @ConfigField(
     *      defaultValues={
     *          "dataaudit"={
     *              "auditable"=true
     *          }
     *      }
     * )
     */
    protected $issueResolution;


 
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return Issue
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set summary
     *
     * @param string $summary
     *
     * @return Issue
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * Get summary
     *
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Issue
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set reporter
     *
     * @param \Oro\Bundle\UserBundle\Entity\User $reporter
     *
     * @return Issue
     */
    public function setReporter(\Oro\Bundle\UserBundle\Entity\User $reporter = null)
    {
        $this->reporter = $reporter;

        return $this;
    }

    /**
     * Get reporter
     *
     * @return \Oro\Bundle\UserBundle\Entity\User
     */
    public function getReporter()
    {
        return $this->reporter;
    }



    /**
     * Set assignee
     *
     * @param \Oro\Bundle\UserBundle\Entity\User $assignee
     *
     * @return Issue
     */
    public function setAssignee(\Oro\Bundle\UserBundle\Entity\User $assignee = null)
    {
        $this->assignee = $assignee;

        return $this;
    }

    /**
     * Get assignee
     *
     * @return \Oro\Bundle\UserBundle\Entity\User
     */
    public function getAssignee()
    {
        return $this->assignee;
    }


    /**
     * Set organization
     *
     * @param \Oro\Bundle\OrganizationBundle\Entity\Organization $organization
     *
     * @return Issue
     */
    public function setOrganization(\Oro\Bundle\OrganizationBundle\Entity\Organization $organization = null)
    {
        $this->organization = $organization;

        return $this;
    }

    /**
     * Get organization
     *
     * @return \Oro\Bundle\OrganizationBundle\Entity\Organization
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * @return User
     */
    public function getOwner()
    {
        return $this->reporter;
    }

    /**
     * @param User $owningUser
     * @return Tag
     */
    public function setOwner($owningUser)
    {
        $this->reporter = $owningUser;

        return $this;
    }

    /**
     * Set workflowStep
     *
     * @param \Oro\Bundle\WorkflowBundle\Entity\WorkflowStep $workflowStep
     *
     * @return Issue
     */
    public function setWorkflowStep(\Oro\Bundle\WorkflowBundle\Entity\WorkflowStep $workflowStep = null)
    {
        $this->workflowStep = $workflowStep;

        return $this;
    }

    /**
     * Get workflowStep
     *
     * @return \Oro\Bundle\WorkflowBundle\Entity\WorkflowStep
     */
    public function getWorkflowStep()
    {
        return $this->workflowStep;
    }

    /**
     * Set workflowItem
     *
     * @param \Oro\Bundle\WorkflowBundle\Entity\WorkflowItem $workflowItem
     *
     * @return Issue
     */
    public function setWorkflowItem(\Oro\Bundle\WorkflowBundle\Entity\WorkflowItem $workflowItem = null)
    {
        $this->workflowItem = $workflowItem;

        return $this;
    }

    /**
     * Get workflowItem
     *
     * @return \Oro\Bundle\WorkflowBundle\Entity\WorkflowItem
     */
    public function getWorkflowItem()
    {
        return $this->workflowItem;
    }

    /**
     * Set issuePriority
     *
     * @param \Oro\Bundle\IssueBundle\Entity\IssuePriority $issuePriority
     *
     * @return Issue
     */
    public function setIssuePriority(\Oro\Bundle\IssueBundle\Entity\IssuePriority $issuePriority = null)
    {
        $this->issuePriority = $issuePriority;

        return $this;
    }

    /**
     * Get issuePriority
     *
     * @return \Oro\Bundle\IssueBundle\Entity\IssuePriority
     */
    public function getIssuePriority()
    {
        return $this->issuePriority;
    }

    /**
     * Set issueResolution
     *
     * @param \Oro\Bundle\IssueBundle\Entity\IssueResolution $issueResolution
     *
     * @return Issue
     */
    public function setIssueResolution(\Oro\Bundle\IssueBundle\Entity\IssueResolution $issueResolution = null)
    {
        $this->issueResolution = $issueResolution;

        return $this;
    }

    /**
     * Get issueResolution
     *
     * @return \Oro\Bundle\IssueBundle\Entity\IssueResolution
     */
    public function getIssueResolution()
    {
        return $this->issueResolution;
    }





    /**
     * Set issueType
     *
     * @param string $issueType
     *
     * @return Issue
     */
    public function setIssueType($issueType)
    {
        $this->issueType = $issueType;

        return $this;
    }

    /**
     * Get issueType
     *
     * @return string
     */
    public function getIssueType()
    {
        return $this->issueType;
    }
}
