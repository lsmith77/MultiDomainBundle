<?php
namespace M4nu\MultiDomainBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class OverrideRouteBasepathsCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $routeBasePaths = $container->getParameter('cmf_routing.dynamic.persistence.phpcr.route_basepaths');
        $domains = $container->getParameter('m4nu_multi_domain.domains');

        $routeBasePathsWithDomains = array();

        foreach ($routeBasePaths as $routeBasePath) {
            foreach ($domains as $domain) {
                $routeBasePathsWithDomains[] = sprintf('%s/%s', $routeBasePath, $domain);
            }
        }

        $container
            ->getDefinition('cmf_routing.phpcr_candidates_prefix')
            ->replaceArgument(0, $routeBasePathsWithDomains)
        ;

        $container
            ->getDefinition('cmf_routing.initializer')
            ->replaceArgument(1, $routeBasePathsWithDomains)
        ;
    }
}
