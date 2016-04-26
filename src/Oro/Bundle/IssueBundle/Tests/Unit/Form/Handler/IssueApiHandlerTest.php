<?php
namespace Oro\Bundle\IssueBundle\Tests\Unit\Form\Handler;

use Oro\Bundle\IssueBundle\Form\Handler\IssueApiHandler;

class IssueApiHandlerTest extends AbstractHandlerTest
{
    public function setUp()
    {
        parent::setUp();
        $this->handler = new IssueApiHandler($this->form, $this->request, $this->manager);
    }
}
