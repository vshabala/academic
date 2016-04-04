<?php

namespace Oro\Bundle\IssueBundle\Form\Handler;

use Oro\Bundle\IssueBundle\Entity\TagManager;

interface IssueHandlerInterface
{
    /**
     * Setter for tag manager
     *
     * @param TagManager $tagManager
     */
    public function setTagManager(TagManager $tagManager);
}


