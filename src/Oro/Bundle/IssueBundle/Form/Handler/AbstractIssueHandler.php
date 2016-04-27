<?php
namespace Oro\Bundle\IssueBundle\Form\Handler;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Oro\Bundle\IssueBundle\Entity\Issue;

abstract class AbstractIssueHandler
{
    /**
     * @var FormInterface
     */
    protected $form;
    /**
     * @var Request
     */
    protected $request;
    /**
     * @var ObjectManager
     */
    protected $manager;

    /**
     * @param FormInterface       $form
     * @param Request             $request
     * @param ObjectManager       $manager
     */
    public function __construct(
        FormInterface $form,
        Request $request,
        ObjectManager $manager
    ) {
        $this->form                = $form;
        $this->request             = $request;
        $this->manager             = $manager;
    }
    /**
     * Process form
     *
     * @param Issue $entity
     * @return bool
     */
    abstract public function process(Issue $entity);
    /**
     * "Success" form handler
     *
     * @param Issue $entity
     */
    protected function onSuccess(Issue $entity)
    {
        $this->manager->persist($entity);
        $this->manager->flush();
    }
    /**
     *
     * @return FormInterface
     */
    public function getForm()
    {
        return $this->form;
    }
}
