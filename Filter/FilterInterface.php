<?php
namespace Olc\Filter;

/**
 * Describes a filter.
 */
interface FilterInterface
{
    /**
     * Filters `$x`.
     *
     * @param unknown $x Data to be filtered
     *
     * @return unknown
     */
    public function filter($x);
}
