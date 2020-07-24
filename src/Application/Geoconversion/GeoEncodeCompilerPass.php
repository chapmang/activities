<?php
declare(strict_types=1);
namespace App\Application\GeoConversion;

use App\Application\GeoConversion\Encoder\GeoEncoderStrategyInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class GeoEncodeCompilerPass
 * @package App\Application\GeoConversion
 */
class GeoEncodeCompilerPass implements CompilerPassInterface
{

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $resolverService = $container->findDefinition(GeoEncode::class);

        $strategyServices = array_keys($container->findTaggedServiceIds(GeoEncoderStrategyInterface::SERVICE_TAG));

        foreach ($strategyServices as $strategyService) {
            $resolverService->addMethodCall('addStrategy', [new Reference($strategyService)]);
        }
    }
}