<?php

namespace Nuwave\Lighthouse\Federation\Resolvers;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Schema;
use GraphQL\Type\SchemaConfig;
use GraphQL\Utils\SchemaExtender;
use Nuwave\Lighthouse\Federation\Schema\SchemaPrinter;
use Nuwave\Lighthouse\GraphQL;
use Nuwave\Lighthouse\Schema\AST\ASTBuilder;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Nuwave\Lighthouse\Schema\TypeRegistry;

class Service
{
    // TODO find a better place for those constants
    const FEDERATION_QUERY_FIELDS = ['_entities', '_service'];
    const FEDERATION_DIRECTIVES = ['key', 'extends', 'external', 'extends', 'requires', 'provides'];

    private GraphQL $graphQL;
    private TypeRegistry $typeRegistry;
    private ASTBuilder $ASTBuilder;

    public function __construct(GraphQL $graphQL, TypeRegistry $typeRegistry, ASTBuilder $ASTBuilder)
    {
        $this->graphQL = $graphQL;
        $this->typeRegistry = $typeRegistry;
        $this->ASTBuilder = $ASTBuilder;
    }

    /**
     * @return array
     */
    public function resolveSdl($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $schema = $resolveInfo->schema;

        $queryFields = [];
        foreach ($schema->getQueryType()->getFields() as $field) {
            if (! in_array($field->name, static::FEDERATION_QUERY_FIELDS)) {
                $queryFields[] = $field;
            }
        }

        $directives = [];
        foreach ($schema->getDirectives() as $directive) {
            if (! in_array($directive->name, static::FEDERATION_DIRECTIVES)) {
                $directives[] = $directive;
            }
        }

        $types = [];
        foreach ($schema->getTypeMap() as $name => $type) {
            if ($type instanceof ObjectType) {
                $types[] = $type;
            }
        }

//        $schemaConfig = SchemaConfig::create();
//        $schemaConfig->setQuery($queryFields);
//        $newSchema = new Schema([
//            'query'        => $schemaConfig,
//            'mutation'     => $schema->getMutationType(),
//            'subscription' => $schema->getSubscriptionType(),
//            'types'        => $types,
//            'directives'   => $directives,
//            'typeLoader'   => $schema->getConfig()->getTypeLoader(),
//        ]);
//        $newSchema->getAstNode();

//        $schema = $this->graphQL->prepSchema();
        $user = $this->typeRegistry->get('User');
        $documentAst = $this->ASTBuilder->documentAST();

        // TODO the new schema should be printed including the inline (federation) directives required for federation to work. We may need to create our own schema printer for this.
        return ['sdl' => SchemaPrinter::doPrint($schema, $documentAst)];
    }
}
