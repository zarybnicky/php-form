<?php
namespace Olc\Data;

class Aggregator
{
    /**
     * @return S
     */
    public function initial()
    {
    }

    /**
     * @param I
     * @return S
     */
    public function prepare($x)
    {
    }

    /**
     * @param S
     * @param S
     * @return S
     */
    public function reduce($x, $y)
    {
    }

    /**
     * @param S
     * @return O
     */
    public function present($x)
    {
    }
}

/*
Use for

METHOD - POST, unless some component specifies GET (?)
ENCTYPE - URL_ENCODED, unless some component specifies MULTIPAT
ERROR LIST - default array(), reduce == array_merge()
RESULT - default aggregator = ArrayAggregator


Other examples

add (+, ., array_merge)
min/max
Top N items (top 2: [a: 10, b: 5, c: 7] => [a: 10, c: 7])
average
histogram
unique values
frequency

see: Bryant - Add All The Things
 */