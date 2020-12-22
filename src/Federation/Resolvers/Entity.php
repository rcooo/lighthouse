<?php

namespace Nuwave\Lighthouse\Federation\Resolvers;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Nuwave\Lighthouse\Support\Utils;

class Entity
{
    public function resolve($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $representations = $args['representations'];

        return array_map(
            function ($representation) use ($resolveInfo) {
                $typeName = $representation['__typename'];

                /** @var ObjectType $type */
                $type = $resolveInfo->schema->getType($typeName);

                if (!$type || $type instanceof ObjectType === false) {
                    throw new \Exception(
                        `The _entities resolver tried to load an entity for type "${$typeName}", but no object type of that name was found in the schema`
                    );
                }


                $resolver = Utils::constructResolver($namespacedClassName, $methodName);

                return $resolver();
            },
            $representations
        );
    }
}
