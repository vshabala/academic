<?php

namespace Oro\Bundle\IssueBundle\Form\Type;

use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use Oro\Bundle\IssueBundle\Entity\Issue;

class IssueType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('summary', 'text', array('label' => 'oro.issue.summary.label'))
            ->add('code', 'text', array('label' => 'oro.issue.code.label', 'required' => true))
            ->add('description', 'textarea', array('label' => 'oro.issue.description.label'))
            ->add('reporter', 'oro_user_select', ['label' => 'oro.issue.reporter.label'])
            ->add('assignee', 'oro_user_select', ['label' => 'oro.issue.assignee.label']);

        $builder
            ->add(
                'issuePriority',
                'translatable_entity',
                [
                    'label' => 'oro.issue.issue_priority.label',
                    'class' => 'Oro\Bundle\IssueBundle\Entity\IssuePriority',
                    'required' => true,
                ]
            );

        $builder
            ->add(
                'issueResolution',
                'translatable_entity',
                [
                    'label' => 'oro.issue.issue_resolution.label',
                    'class' => 'Oro\Bundle\IssueBundle\Entity\IssueResolution',
                    'required' => false,
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
                    'label' => 'oro.issue.related_issues.label',
                    'class' => 'Oro\Bundle\IssueBundle\Entity\Issue',
                    'multiple' => true,
                    'required' => false
                ]
            );

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();
            if ($data instanceof Issue) {
                if ($data->getIssueType() != "Subtask") {
                    $form->add(
                        'issueType',
                        'choice',
                        [
                            'multiple' => false,
                            'label' => 'oro.issue.issue_type.label',
                            'choices' => [
                                Issue::BUG => 'oro.issue.form.issue_type.Bug.label',
                                Issue::TASK => 'oro.issue.form.issue_type.Task.label',
                                Issue::STORY => 'oro.issue.form.issue_type.Story.label',
                            ],
                        ]
                    );
                }
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Oro\Bundle\IssueBundle\Entity\Issue',

                'ownership_disabled' => true,

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
