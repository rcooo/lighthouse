<?php

namespace Nuwave\Lighthouse\Schema\Directives;

use Nuwave\Lighthouse\Support\Contracts\Directive;

/**
 * This directive exists as a placeholder and can be used
 * to point to a custom subscription class.
 *
 * @see \Nuwave\Lighthouse\Schema\Types\GraphQLSubscription
 */
class SubscriptionDirective implements Directive
{
    const NAME = 'subscription';

    /**
     * Name of the directive.
     *
     * @return string
     */
    public function name(): string
    {
        return self::NAME;
    }

    public static function definition(): string
    {
        return '
"""
Reference a class to handle the broadcasting of a subscription to clients.
The given class must extend `\Nuwave\Lighthouse\Schema\Types\GraphQLSubscription`.
"""
directive @subscription(
  """
  A reference to a subclass of `\Nuwave\Lighthouse\Schema\Types\GraphQLSubscription`.
  """
  class: String!
) on FIELD_DEFINITION';
    }
}
