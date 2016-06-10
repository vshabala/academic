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
 * @ORM\EntityListeners({"Oro\Bundle\IssueBundle\EventListener\IssueUpdateListener"})
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

    const BUG = 'Bug';
    const TASK = 'Task';
    const STORY = 'Story';
    const SUB_TASK = 'Subtask';


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
     *      "importexport"={
     *          "excluded"=true
     *      }
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
     *      "importexport"={
     *          "excluded"=true
     *      }
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
     *          "importexport"={
     *          "excluded"=true
     *      }
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
       
        if ($this->getCode() && $this->getSummary()) {
            return $this->getCode().": ".$this->getSummary();
        } else {
            return (string)null;
        }
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
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
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
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
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
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
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
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
     * @return \Oro\Bundle\UserBundle\Entity\User
     */
    public function getReporter()
    {
        return $this->reporter;
    }

    /**
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
     * @return \Oro\Bundle\UserBundle\Entity\User
     */
    public function getAssignee()
    {
        return $this->assignee;
    }

    /**
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
     * @return Issue
     */
    public function setOwner($owningUser)
    {
        $this->reporter = $owningUser;

        return $this;
    }

    /**
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
     * @return \Oro\Bundle\WorkflowBundle\Entity\WorkflowStep
     */
    public function getWorkflowStep()
    {
        return $this->workflowStep;
    }

    /**
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
     * @return \Oro\Bundle\WorkflowBundle\Entity\WorkflowItem
     */
    public function getWorkflowItem()
    {
        return $this->workflowItem;
    }

    /**
     * @param IssuePriority $issuePriority
     */
    public function setIssuePriority($issuePriority)
    {
        $this->issuePriority = $issuePriority;
    }

    /**
     * @return \Oro\Bundle\IssueBundle\Entity\IssuePriority
     */
    public function getIssuePriority()
    {
        return $this->issuePriority;
    }

    /**
     * @param IssueResolution $issueResolution
     * @return Issue
     */
    public function setIssueResolution(\Oro\Bundle\IssueBundle\Entity\IssueResolution $issueResolution = null)
    {
        $this->issueResolution = $issueResolution;

    }

    /**
     * @return \Oro\Bundle\IssueBundle\Entity\IssueResolution
     */
    public function getIssueResolution()
    {
        return $this->issueResolution;
    }

    /**
     * @param string $issueType
     * @return Issue
     */
    public function setIssueType($issueType)
    {
        $this->issueType = $issueType;
        return $this;
    }

    /**
     * @return string
     */
    public function getIssueType()
    {
        return $this->issueType;
    }

    /**
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
     * @return \Oro\Bundle\IssueBundle\Entity\Issue
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
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
     * @param \Oro\Bundle\IssueBundle\Entity\Issue $child
     */
    public function removeChild(\Oro\Bundle\IssueBundle\Entity\Issue $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
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
     * @param \Oro\Bundle\UserBundle\Entity\User $collaborator
     */
    public function removeCollaborator(\Oro\Bundle\UserBundle\Entity\User $collaborator)
    {
        $this->collaborators->removeElement($collaborator);
    }

    /**
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
     * @param \Oro\Bundle\IssueBundle\Entity\Issue $relatedIssue
     */
    public function removeRelatedIssue(\Oro\Bundle\IssueBundle\Entity\Issue $relatedIssue)
    {
        $this->relatedIssues->removeElement($relatedIssue);
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRelatedIssues()
    {
        return $this->relatedIssues;
    }

    /**
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
     * @param \Oro\Bundle\IssueBundle\Entity\Issue $issuesRelated
     */
    public function removeIssuesRelated(\Oro\Bundle\IssueBundle\Entity\Issue $issuesRelated)
    {
        $this->issuesRelated->removeElement($issuesRelated);
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIssuesRelated()
    {
        return $this->issuesRelated;
    }
}

