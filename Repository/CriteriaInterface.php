<?php
namespace hollis1024\lumen\struct\Repository;

/**
 * Interface CriteriaInterface
 * @package App\Components\Repository
 */
interface CriteriaInterface
{
    /**
     * Apply criteria in query repository
     *
     * @param                     $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository);
}
