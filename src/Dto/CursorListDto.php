<?php

namespace hollis1024\lumen\struct\Dto;


class CursorListDto extends BaseDto
{
    public $list;

    public $count;

    public $last_cursor;

    public $size;
}