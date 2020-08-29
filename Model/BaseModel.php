<?php

namespace hollis1024\LumenStruct\Model;


use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    protected $dateFormat = 'U';

    protected function newBaseQueryBuilder()
    {
        $connection = $this->getConnection();
        $query = $this->getQuery();
        return new $query(
            $connection, $connection->getQueryGrammar(), $connection->getPostProcessor()
        );
    }

    protected function getQuery() {
        return QueryBuilder::class;
    }

}