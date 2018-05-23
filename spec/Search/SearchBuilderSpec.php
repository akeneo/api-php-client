<?php

namespace spec\Akeneo\Pim\ApiClient\Builder;

use Akeneo\Pim\ApiClient\Search\Operator;
use Akeneo\Pim\ApiClient\Search\SearchBuilder;
use PhpSpec\ObjectBehavior;

class SearchBuilderSpec  extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(SearchBuilder::class);
    }

    function it_builds_a_search_with_a_single_filter_without_options()
    {
        $this->addFilter('foo' , Operator::EQUAL, 'bar')->shouldReturn($this);

        $this->getFilters()->shouldReturn(
            [
                'foo'=> [
                    [
                        'operator' => Operator::EQUAL,
                        'value'    => 'bar',
                    ]
                ]
            ]
        );
    }

    function it_builds_a_search_with_a_single_filter_without_value_nor_option()
    {
        $this->addFilter('family' , Operator::IS_EMPTY)->shouldReturn($this);

        $this->getFilters()->shouldReturn(
            [
                'family'=> [
                    [
                        'operator' => Operator::IS_EMPTY,
                    ]
                ]
            ]
        );
    }

    function it_builds_a_search_with_a_single_filter_with_some_options()
    {
        $this
            ->addFilter('foo' , Operator::EQUAL, 'bar', ['scope' => 'chan1', 'locale' => 'en_US'])
            ->shouldReturn($this);

        $this->getFilters()->shouldReturn(
            [
                'foo'=> [
                    [
                        'operator' => Operator::EQUAL,
                        'value'    => 'bar',
                        'scope'    => 'chan1',
                        'locale'   => 'en_US',
                    ]
                ]
            ]
        );
    }

    function it_builds_a_search_with_several_filters_on_the_same_property()
    {
        $this->addFilter('foo' , Operator::EQUAL, 'bar')->shouldReturn($this);
        $this->addFilter('foo' , Operator::NOT_EQUAL, 42)->shouldReturn($this);

        $this->getFilters()->shouldReturn(
            [
                'foo'=> [
                    [
                        'operator' => Operator::EQUAL,
                        'value'    => 'bar',
                    ],
                    [
                        'operator' => Operator::NOT_EQUAL,
                        'value'    => 42,
                    ],
                ]
            ]
        );
    }

    function it_builds_a_search_with_filters_on_several_properties()
    {
        $this->addFilter('foo' , Operator::EQUAL, 'bar')->shouldReturn($this);
        $this->addFilter('family' , Operator::IN, ['tshirts', 'mugs'])->shouldReturn($this);

        $this->getFilters()->shouldReturn(
            [
                'foo'=> [
                    [
                        'operator' => Operator::EQUAL,
                        'value'    => 'bar',
                    ],
                ],
                'family' => [
                    [
                        'operator' => Operator::IN,
                        'value'    => ['tshirts', 'mugs']
                    ]
                ]
            ]
        );
    }
}
