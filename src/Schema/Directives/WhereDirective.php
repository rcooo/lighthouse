<?php

namespace Nuwave\Lighthouse\Schema\Directives;

use Nuwave\Lighthouse\Support\Contracts\ArgBuilderDirective;

class WhereDirective extends BaseDirective implements ArgBuilderDirective
{
    /**
     * Name of the directive.
     *
     * @return string
     */
    public function name(): string
    {
        return 'where';
    }

    public static function definition(): string
    {
        return '
"""
Use an input value as a [where filter](https://laravel.com/docs/queries#where-clauses).
"""
directive @where(
  """
  Specify the operator to use within the WHERE condition.
  """
  operator: String = "="

  """
  Specify the database column to compare. 
  Only required if database column has a different name than the attribute in your schema.
  """
  key: String

  """
  Use Laravel\'s where clauses upon the query builder.
  """
  clause: String
) on ARGUMENT_DEFINITION | INPUT_FIELD_DEFINITION';
    }

    /**
     * Add any "WHERE" clause to the builder.
     *
     * @param  \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder  $builder
     * @param  mixed  $value
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function handleBuilder($builder, $value)
    {
        // Allow users to overwrite the default "where" clause, e.g. "whereYear"
        $clause = $this->directiveArgValue('clause', 'where');

        return $builder->{$clause}(
            $this->directiveArgValue('key', $this->definitionNode->name->value),
            $operator = $this->directiveArgValue('operator', '='),
            $value
        );
    }
}
