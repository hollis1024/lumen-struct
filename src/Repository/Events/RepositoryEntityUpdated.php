<?php
namespace hollis1024\lumen\struct\Repository\Events;

class RepositoryEntityUpdated extends RepositoryEventBase
{
    /**
     * @var string
     */
    protected $action = "updated";
}
