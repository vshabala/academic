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
     * @ConfigField(
     *   defaultValues={
     *      "dataaudit"={
     *           "auditable"=true
     *       },
              "importexport"={
     *          "excluded"=true
     *      }
     *   }
     * )
     */
    protected $id;

    /**
     * @var string $code
     *
     * @ORM\Column(name="code", type="string", length=50, nullable=false)
     * @ConfigField(
     *   defaultValues={
     *      "dataaudit"={
     *           "auditable"=true
     *       },
     *       "importexport"={
     *           "header"="Code",
     *           "order"="10"
     *        }
     *   }
     * )
     */
    protected $code;

    /**
     * @var string $summary
     *
     * @ORM\Column(name="summary", type="text", nullable=false)
     * @ConfigField(
     *   defaultValues={
     *      "dataaudit"={
     *           "auditable"=true
     *       },
     *       "importexport"={
     *           "header"="Summary",
     *           "order"="15"
     *       }
     *   }
     * )
     */
    protected $summary;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     * @ConfigField(
     *   defaultValues={
     *      "dataaudit"={
     *           "auditable"=true
     *       },
     *       "importexport"={
     *           "header"="Description",
     *           "order"="20"
     *       }
     *   }
     * )
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(name="issue_type", type="string", length=32, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Regex("/^(Bug|Task|Subtask|Story)/")
     * @ConfigField(
     *   defaultValues={
     *      "dataaudit"={
     *           "auditable"=true
     *       },
     *         "importexport"={
     *           "header"="Type",
     *           "order"="25"
     *       }
     *   }
     * )
     */
    protected $issueType;

    /**
     * @var IssuePriority
     *
     * @ORM\ManyToOne(targetEntity="IssuePriority")
     * @ORM\JoinColumn(name="issue_priority_name", referencedColumnName="name", onDelete="SET NULL")
     * @ConfigField(
     *   defaultValues={
     *      "dataaudit"={
     *           "auditable"=true
     *       },
     *       "importexport"={
     *           "header"="Priority",
     *           "order"="30"
     *       }
     *   }
     * )
     */
    protected $issuePriority;

    /**
     * @var IssueResolution
     *
     * @ORM\ManyToOne(targetEntity="IssueResolution")
     * @ORM\JoinColumn(name="issue_resolution_name", referencedColumnName="name", onDelete="SET NULL")
     * @ConfigField(
     *   defaultValues={
     *      "dataaudit"={
     *           "auditable"=true
     *       },
     *       "importexport"={
     *           "header"="Resolution",
     *           "order"="35"
     *       }
     *   }
     * )
     */
    protected $issueResolution;


    /**
     * @var parent
     *
     * @ORM\ManyToOne(targetEntity="Issue", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     * @ConfigField(
     *   defaultValues={
     *      "dataaudit"={
     *           "auditable"=true
     *       },
     *       "importexport"={
     *           "header"="Parent",
     *           "order"="40"
     *       }
     *   }
     * )
     */
    protected $parent;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany( targetEntity="Issue", mappedBy="parent",  cascade={"ALL"}, fetch="EXTRA_LAZY")
     * @ConfigField(
     *  defaultValues={
     *      "importexport"={
     *          "excluded"=true
     *      }
     *  }
     * )
     */
    protected $children;



    /**
     * @var reporter
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_reporter_id", referencedColumnName="id", onDelete="SET NULL")
     * @ConfigField(
     *   defaultValues={
     *      "dataaudit"={
     *           "auditable"=true
     *       },
     *       "importexport"={
     *           "header"="Reporter",
     *           "order"="45"
     *       }
     *   }
     * )
     */
    protected $reporter;

    /**
     * @var assignee
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_assignee_id", referencedColumnName="id", onDelete="SET NULL")
     * @ConfigField(
     *   defaultValues={
     *      "dataaudit"={
     *           "auditable"=true
     *       },
     *       "importexport"={
     *           "header"="Assignee",
     *           "order"="50"
     *       }
     *   }
     * )
     */
    protected $assignee;

    /**
     * @var Organization
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\OrganizationBundle\Entity\Organization")
     * @ORM\JoinColumn(name="organization_id", referencedColumnName="id", onDelete="SET NULL")
     * @ConfigField(
     *   defaultValues={
     *      "dataaudit"={
     *           "auditable"=true
     *       },
     *       "importexport"={
     *           "header"="Oragnization",
     *           "order"="55"
     *        }
     *   }
     * )
     */
    protected $organization;

    /**
     * @var WorkflowItem
     *
     * @ORM\OneToOne(targetEntity="Oro\Bundle\WorkflowBundle\Entity\WorkflowItem")
     * @ORM\JoinColumn(name="workflow_item_id", referencedColumnName="id", onDelete="SET NULL")
     * @ConfigField(
     *   defaultValues={
     *      "dataaudit"={
     *           "auditable"=true
     *       },
     *       "importexport"={
     *           "header"="Status",
     *           "order"="60"
     *       }
     *   }
     * )
     */
    protected $workflowItem;

    /**
     * @var WorkflowStep
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\WorkflowBundle\Entity\WorkflowStep")
     * @ORM\JoinColumn(name="workflow_step_id", referencedColumnName="id", onDelete="SET NULL")
     *  defaultValues={
     *      "importexport"={
     *          "excluded"=true
     *      }
     *  }
     * )
     */
    protected $workflowStep;


    /**
     * @ORM\ManyToMany(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinTable(name="oro_issue_collaborator",
     *      joinColumns={@ORM\JoinColumn(name="issue_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     *      )
     * @ConfigField(
     *   defaultValues={
     *       "importexport"={
     *           "excluded"=true
     *       }
     *   }
     * )
     */
    protected $collaborators;


    /**
     * @ORM\ManyToMany(targetEntity="Issue", inversedBy="issuesRelated")
     * @ORM\JoinTable(name="oro_issue_related")
     * @ConfigField(
     *   defaultValues={
     *       "importexport"={
     *           "excluded"=true
     *       }
     *   }
     * )
     **/
    protected $relatedIssues;

    /**
     * @ORM\ManyToMany(targetEntity="Issue", mappedBy="relatedIssues")
     * @ConfigField(
     *   defaultValues={
     *       "importexport"={
     *           "excluded"=true
     *       }
     *   }
     * )
     */
    protected $issuesRelated;

    /**
     * Issue constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->collaborators = new ArrayCollection();
        $this->children = new ArrayCollection();
    }


    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getCode()." : ".$this->getSummary();
    }

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

    /**
     * Set parent
     *
     * @param \Oro\Bundle\IssueBundle\Entity\Issue $parent
     *
     * @return Issue
     */
    public function setParent(\Oro\Bundle\IssueBundle\Entity\Issue $parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Oro\Bundle\IssueBundle\Entity\Issue
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add child
     *
     * @param \Oro\Bundle\IssueBundle\Entity\Issue $child
     *
     * @return Issue
     */
    public function addChild(\Oro\Bundle\IssueBundle\Entity\Issue $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param \Oro\Bundle\IssueBundle\Entity\Issue $child
     */
    public function removeChild(\Oro\Bundle\IssueBundle\Entity\Issue $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Add collaborator
     *
     * @param \Oro\Bundle\UserBundle\Entity\User $collaborator
     *
     * @return Issue
     */
    public function addCollaborator(\Oro\Bundle\UserBundle\Entity\User $collaborator)
    {

        if (!$this->collaborators->contains($collaborator)) {
            $this->collaborators->add($collaborator);
        }

        return $this;
    }

    /**
     * Remove collaborator
     *
     * @param \Oro\Bundle\UserBundle\Entity\User $collaborator
     */
    public function removeCollaborator(\Oro\Bundle\UserBundle\Entity\User $collaborator)
    {
        $this->collaborators->removeElement($collaborator);
    }

    /**
     * Get collaborators
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCollaborators()
    {
        return $this->collaborators;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return __CLASS__;
    }

    /**
     * Add relatedIssue
     *
     * @param \Oro\Bundle\IssueBundle\Entity\Issue $relatedIssue
     *
     * @return Issue
     */
    public function addRelatedIssue(\Oro\Bundle\IssueBundle\Entity\Issue $relatedIssue)
    {
        $this->relatedIssues[] = $relatedIssue;

        return $this;
    }

    /**
     * Remove relatedIssue
     *
     * @param \Oro\Bundle\IssueBundle\Entity\Issue $relatedIssue
     */
    public function removeRelatedIssue(\Oro\Bundle\IssueBundle\Entity\Issue $relatedIssue)
    {
        $this->relatedIssues->removeElement($relatedIssue);
    }

    /**
     * Get relatedIssues
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRelatedIssues()
    {
        return $this->relatedIssues;
    }

    /**
     * Add issuesRelated
     *
     * @param \Oro\Bundle\IssueBundle\Entity\Issue $issuesRelated
     *
     * @return Issue
     */
    public function addIssuesRelated(\Oro\Bundle\IssueBundle\Entity\Issue $issuesRelated)
    {
        $this->issuesRelated[] = $issuesRelated;

        return $this;
    }

    /**
     * Remove issuesRelated
     *
     * @param \Oro\Bundle\IssueBundle\Entity\Issue $issuesRelated
     */
    public function removeIssuesRelated(\Oro\Bundle\IssueBundle\Entity\Issue $issuesRelated)
    {
        $this->issuesRelated->removeElement($issuesRelated);
    }

    /**
     * Get issuesRelated
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIssuesRelated()
    {
        return $this->issuesRelated;
    }

}
