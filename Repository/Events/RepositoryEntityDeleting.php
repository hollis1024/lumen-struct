<?php
namespace hollis1024\lumen\struct\Repository\Events;

class RepositoryEntityDeleting extends RepositoryEventBase
{
    /**
     * @var string
     */
    protected $action = "deleting";
}
