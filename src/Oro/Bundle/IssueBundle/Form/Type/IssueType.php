<?php

namespace Oro\Bundle\IssueBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class IssueType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('summary', 'text', array('label' => 'oro.issue.form.summary.label'))
            ->add('code', 'text', array('label' => 'oro.issue.form.code.label', 'required' => true))
            ->add('description', 'oro_resizeable_rich_text', array('label' => 'oro.issue.form.description.label'))
            ->add('reporter', 'oro_user_select', ['label' => 'oro.issue.form.reporter.label'])
            ->add('assignee', 'oro_user_select', ['label' => 'oro.issue.form.assignee.label'])
           // ->add('tags', 'oro_tag_entity_tags_selector', ['label' => 'oro.issue.summary.label'])
            ->add('save', 'submit', array('label' => 'Create Issue'));
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Oro\Bundle\IssueBundle\Entity\Issue',
                'intention'  => 'issue',
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
