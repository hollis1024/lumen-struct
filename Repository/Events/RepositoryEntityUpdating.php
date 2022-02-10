<?php
namespace hollis1024\lumen\struct\Repository\Events;

class RepositoryEntityUpdating extends RepositoryEventBase
{
    /**
     * @var string
     */
    protected $action = "updating";
}
