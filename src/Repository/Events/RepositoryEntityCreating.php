<?php

namespace hollis1024\lumen\struct\Repository\Events;

use Illuminate\Database\Eloquent\Model;
use hollis1024\lumen\struct\Repository\RepositoryInterface;

class RepositoryEntityCreating extends RepositoryEventBase
{
    /**
     * @var string
     */
    protected $action = "creating";

    public function __construct(RepositoryInterface $repository, array $model)
    {
        parent::__construct($repository);
        $this->model = $model;
    }
}
