<?php

namespace App\Domain\DataTypes\Spatial\DBAL\Platform;

use App\Domain\DataTypes\Spatial\DBAL\Types\AbstractSpatialType;
use App\Domain\DataTypes\Spatial\DBAL\Types\GeographyType;
use App\Domain\DataTypes\Spatial\Types\Geometry\GeometryInterface;

class SqlServer extends AbstractPlatform
{

    public const DEFAULT_SRID = 4326;

    /**
     * Convert to database value.
     *
     * @param AbstractSpatialType $type  The spatial type
     * @param GeometryInterface   $value The geometry interface
     *
     * @return string
     */
    public function convertToDatabaseValue(AbstractSpatialType $type, GeometryInterface $value)
    {

        return sprintf('%s', parent::convertToDatabaseValue($type, $value));
    }

    /**
     * @inheritDoc
     */
    public function convertToDatabaseValueSql(AbstractSpatialType $type, $sqlExpr)
    {
        if ($type instanceof GeographyType) {
            return sprintf('geography::STGeomFromText(%s, %s)', $sqlExpr, self::DEFAULT_SRID);
        }

        return sprintf('geometry::STGeomFromText(%s)', $sqlExpr);
    }

    /**
     * @inheritDoc
     */
    public function convertToPhpValueSql(AbstractSpatialType $type, $sqlExpr)
    {
        return sprintf('%s.STAsText()', $sqlExpr);
    }

    /**
     * @inheritDoc
     */
    public function getSqlDeclaration(array $fieldDeclaration)
    {
        $typeFamily = $fieldDeclaration['type']->getTypeFamily();
        return strtoupper($typeFamily);
    }
}