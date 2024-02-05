<?php

    namespace App\Interfaces;

    interface UserRepositoryInterface
    {
        public function get_remain($id);
        public function add_track($driver,$pd,$qty,$document,$update = null);
    }
