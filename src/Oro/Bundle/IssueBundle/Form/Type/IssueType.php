<?php

namespace Oro\Bundle\IssueBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class IssueType extends AbstractType
{
    /**
     * @var array
     */
    private $issueTypes;

    public function __construct(array $issueTypes)
    {
        $this->issueTypes = $issueTypes;
    }


    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('summary', 'text', array('label' => 'oro.issue.form.summary.label'))
            ->add('code', 'text', array('label' => 'oro.issue.form.code.label', 'required' => true))
            ->add('description', 'textarea', array('label' => 'oro.issue.form.description.label'))
            ->add('reporter', 'oro_user_select', ['label' => 'oro.issue.form.reporter.label'])
            ->add('assignee', 'oro_user_select', ['label' => 'oro.issue.form.assignee.label']);

        $builder
            ->add(
                'issueType',
                'choice',
                [
                    'multiple' => false,
                    'label' => 'oro.issue.form.type.label',
                    'choices' => $this->issueTypes,
                ]
            );

        $builder
            ->add(
                'issuePriority',
                'translatable_entity',
                [
                    'label' => 'oro.issue.form.issue_priority.label',
                    'class' => 'Oro\Bundle\IssueBundle\Entity\IssuePriority',
                    'required' => true,
                    'query_builder' => function (EntityRepository $repository) {
                        return $repository->createQueryBuilder('priority')->orderBy('priority.order');
                    }
                ]
            );
        
        $builder
            ->add(
                'issueResolution',
                'translatable_entity',
                [
                    'label' => 'oro.issue.form.issue_resolution.label',
                    'class' => 'Oro\Bundle\IssueBundle\Entity\IssueResolution',
                    'required' => true,
                    'query_builder' => function (EntityRepository $repository) {
                        return $repository->createQueryBuilder('resolution')->orderBy('resolution.order');
                    }
                ]
            );
        $builder
            ->add(
                'relatedIssues',
                'translatable_entity',
                [
                    'label' => 'oro.issue.related.label',
                    'class' => 'Oro\Bundle\IssueBundle\Entity\Issue',
                    'multiple' => true,
                    'required' => false
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Oro\Bundle\IssueBundle\Entity\Issue',

                'ownership_disabled'      => true,

            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'oro_issue_issue';
    }
}
