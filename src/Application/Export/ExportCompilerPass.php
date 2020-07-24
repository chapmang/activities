<?php
declare(strict_types=1);
namespace App\Application\Export;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class ExportCompilerPass
 * @package App\Application\Export
 */
class ExportCompilerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $resolverService = $container->findDefinition(Export::class);

        $strategyServices = array_keys($container->findTaggedServiceIds(ExportStrategyInterface::SERVICE_TAG));

        foreach ($strategyServices as $strategyService) {
            $resolverService->addMethodCall('addStrategy', [new Reference($strategyService)]);
        }
    }
}