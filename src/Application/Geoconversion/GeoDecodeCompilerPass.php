<?php
declare(strict_types=1);
namespace App\Application\GeoConversion;

use App\Application\GeoConversion\Decoder\GeoDecoderStrategyInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class GeoDecodeCompilerPass implements CompilerPassInterface
{

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $resolverService = $container->findDefinition(GeoDecode::class);

        $strategyServices = array_keys($container->findTaggedServiceIds(GeoDecoderStrategyInterface::SERVICE_TAG));

        foreach ($strategyServices as $strategyService) {
            $resolverService->addMethodCall('addStrategy', [new Reference($strategyService)]);
        }
    }
}
