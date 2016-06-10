<?php

namespace Oro\Bundle\IssueBundle\Form\Handler;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\Common\Persistence\ObjectManager;

use Oro\Bundle\IssueBundle\Entity\Issue;

class IssueHandler extends AbstractIssueHandler
{

    public function process(Issue $entity)
    {   

        $this->form->setData($entity);
        if (in_array($this->request->getMethod(), array('POST', 'PUT'))) {
            $this->form->submit($this->request);
            if ($this->form->isValid()) {
                $this->manager->persist($entity);
                $this->manager->flush();
                return true;
            }
        }
        return false;
    }
}
