<?php
namespace hollis1024\lumen\struct\Repository\Events;


class RepositoryEntityCreated extends RepositoryEventBase
{
    /**
     * @var string
     */
    protected $action = "created";
}
