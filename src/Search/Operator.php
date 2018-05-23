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
    const EQUAL = '=';
    const NOT_EQUAL = '!=';
    const GREATER_THAN = '>';
    const GREATER_THAN_OR_EQUAL = '>=';
    const LOWER_THAN = '<';
    const LOWER_THAN_OR_EQUAL = '<=';
    const IN = 'IN';
    const NOT_IN = 'NOT IN';
    const IN_OR_UNCLASSIFIED = 'IN OR UNCLASSIFIED';
    const IN_CHILDREN = 'IN CHILDREN';
    const NOT_IN_CHILDREN = 'NOT IN CHILDREN';
    const UNCLASSIFIED = 'UNCLASSIFIED';
    const GREATER_THAN_ON_ALL_LOCALES = 'GREATER THAN ON ALL LOCALES';
    const GREATER_OR_EQUALS_THAN_ON_ALL_LOCALES = 'GREATER OR EQUALS THAN ON ALL LOCALES';
    const LOWER_THAN_ON_ALL_LOCALES = 'LOWER THAN ON ALL LOCALES';
    const LOWER_OR_EQUALS_THAN_ON_ALL_LOCALES = 'LOWER OR EQUALS THAN ON ALL LOCALES';
    const IS_EMPTY = 'EMPTY';
    const NOT_EMPTY = 'NOT EMPTY';
    const BETWEEN = 'BETWEEN';
    const NOT_BETWEEN = 'NOT BETWEEN';
    const SINCE_LAST_N_DAYS = 'SINCE LAST N DAYS';
    const STARTS_WITH = 'STARTS WITH';
    const ENDS_WITH = 'ENDS WITH';
    const CONTAINS = 'CONTAINS';
    const DOES_NOT_CONTAIN = 'DOES NOT CONTAIN';
}
