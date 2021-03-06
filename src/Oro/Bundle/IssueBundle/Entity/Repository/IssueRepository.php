<?php

namespace Oro\Bundle\IssueBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * IssueRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class IssueRepository extends EntityRepository
{
    /**
     * @return array
     */
    public function countIssuesPerStatus()
    {
        $qb = $this->createQueryBuilder('issue');
        $qb->select('workflow.label as label', 'count(issue.id) as number')

            ->leftJoin('OroWorkflowBundle:WorkflowStep', 'workflow', 'WITH', 'workflow.id = issue.workflowStep')
            ->groupBy('issue.workflowStep');
        
        return $qb->getQuery()->getArrayResult();
    }
}
