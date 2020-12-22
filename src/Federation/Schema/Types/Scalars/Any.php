<?php

namespace Nuwave\Lighthouse\Federation\Schema\Types\Scalars;

use GraphQL\Type\Definition\ScalarType;

class Any extends ScalarType
{
    public function serialize($value)
    {
        // TODO: Implement serialize() method.
    }

    public function parseValue($value)
    {
        return $value;
    }

    public function parseLiteral($valueNode, ?array $variables = null)
    {
        // TODO: Implement parseLiteral() method.
    }
}
