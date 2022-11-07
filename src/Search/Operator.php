<?php

namespace Akeneo\Pim\ApiClient\Search;

/**
 * This class contains the list of operators to use in filters to search resources.
 *
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
final class Operator
{
    public const EQUAL = '=';
    public const NOT_EQUAL = '!=';
    public const GREATER_THAN = '>';
    public const GREATER_THAN_OR_EQUAL = '>=';
    public const LOWER_THAN = '<';
    public const LOWER_THAN_OR_EQUAL = '<=';
    public const IN = 'IN';
    public const NOT_IN = 'NOT IN';
    public const IN_OR_UNCLASSIFIED = 'IN OR UNCLASSIFIED';
    public const IN_CHILDREN = 'IN CHILDREN';
    public const NOT_IN_CHILDREN = 'NOT IN CHILDREN';
    public const UNCLASSIFIED = 'UNCLASSIFIED';
    public const GREATER_THAN_ON_ALL_LOCALES = 'GREATER THAN ON ALL LOCALES';
    public const GREATER_OR_EQUALS_THAN_ON_ALL_LOCALES = 'GREATER OR EQUALS THAN ON ALL LOCALES';
    public const LOWER_THAN_ON_ALL_LOCALES = 'LOWER THAN ON ALL LOCALES';
    public const LOWER_OR_EQUALS_THAN_ON_ALL_LOCALES = 'LOWER OR EQUALS THAN ON ALL LOCALES';
    public const IS_EMPTY = 'EMPTY';
    public const NOT_EMPTY = 'NOT EMPTY';
    public const BETWEEN = 'BETWEEN';
    public const NOT_BETWEEN = 'NOT BETWEEN';
    public const SINCE_LAST_N_DAYS = 'SINCE LAST N DAYS';
    public const STARTS_WITH = 'STARTS WITH';
    public const ENDS_WITH = 'ENDS WITH';
    public const CONTAINS = 'CONTAINS';
    public const DOES_NOT_CONTAIN = 'DOES NOT CONTAIN';
    public const AT_LEAST_COMPLETE = 'AT LEAST COMPLETE';
    public const AT_LEAST_INCOMPLETE = 'AT LEAST INCOMPLETE';
    public const ALL_COMPLETE = 'ALL COMPLETE';
    public const ALL_INCOMPLETE = 'ALL INCOMPLETE';
}
