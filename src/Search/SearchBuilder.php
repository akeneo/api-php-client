<?php

namespace Akeneo\Pim\ApiClient\Search;

/**
 * Helper to build search filters to apply when requesting a list of resources.
 * After setting your filters, you have to pass them to the query parameter "search".
 *
 * @author    Laurent Petard <laurent.petard@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class SearchBuilder
{
    /** @var array */
    protected $filters = [];

    /**
     * Add a filter on a property with an operator and a value.
     *
     * @see https://api.akeneo.com/documentation/filter.html for the list of properties and operators.
     *
     * @param string      $property property on which the filter will be applied.
     * @param string      $operator operator of the filter.
     * @param mixed|null  $value    value of the filter. Should not be defined for certain specifics operators.
     * @param array       $options  optionals parameters to apply to the filter (as scope or locale).
     *
     * @return SearchBuilder
     */
    public function addFilter(string $property, string $operator, $value = null, array $options = []): self
    {
        $filter = ['operator'=> $operator];

        if (null !== $value) {
            $filter['value'] = $value;
        }

        $this->filters[$property][] = $filter + $options;

        return $this;
    }

    public function getFilters(): array
    {
        return $this->filters;
    }
}
