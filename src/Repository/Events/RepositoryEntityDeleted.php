<?php
namespace hollis1024\lumen\struct\Repository\Events;

class RepositoryEntityDeleted extends RepositoryEventBase
{
    /**
     * @var string
     */
    protected $action = "deleted";
}
