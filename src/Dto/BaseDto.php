<?php

namespace hollis1024\lumen\struct\Dto;


use hollis1024\lumen\struct\BaseObject;
use hollis1024\lumen\struct\Traits\ModelAttributes;

class BaseDto extends BaseObject
{
    use ModelAttributes;

    public static function builder($model)
    {
        $dto = null;
        if ($model) {
            $dto = new static();
            $dto->setAttributes(collect($model)->toArray());
        }

        return $dto;
    }
}