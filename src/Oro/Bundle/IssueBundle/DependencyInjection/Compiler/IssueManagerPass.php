<?php

namespace Oro\Bundle\IssueBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class IssueManagerPass implements CompilerPassInterface
{
    const SERVICE_KEY = 'oro_issue.issue.manager';
    const TAG = 'oro_issue.issue_manager';

    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(self::SERVICE_KEY)) {
            return;
        }

        $taggedServices = $container->findTaggedServiceIds(self::TAG);
        foreach ($taggedServices as $id => $tagAttributes) {
            $container->getDefinition($id)->addMethodCall('setIssueManager', array(new Reference(self::SERVICE_KEY)));
        }
    }
}
