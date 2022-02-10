<?php

namespace hollis1024\lumen\struct\Model;


use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    protected $dateFormat = 'U';

    public function getCreatedAtAttribute($value) {
        return $this->fromDateTime($value);
    }

    public function getUpdatedAtAttribute($value) {
        return $this->fromDateTime($value);
    }

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