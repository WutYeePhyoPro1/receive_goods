<?php

namespace App\Interfaces;

interface ActionRepositoryInterface
{
    public function get_remain($id);
    public function add_track($driver,$pd,$qty,$document,$update = null,$unit,$per);
}
