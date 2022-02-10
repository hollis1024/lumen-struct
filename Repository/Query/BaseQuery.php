<?php

namespace hollis1024\lumen\struct\Repository\Query;



use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\Grammars\Grammar;
use Illuminate\Database\Query\Processors\Processor;

class BaseQuery extends Builder
{
    public function __construct(ConnectionInterface $connection, Grammar $grammar = null, Processor $processor = null)
    {
        parent::__construct($connection, $grammar, $processor);
    }

    public function getByProperty($property, $value)
    {
        return $this->where($property, $value);
    }

    public function getByPropertys($property, $values)
    {
        return $this->whereIn($property, $values);
    }
}
