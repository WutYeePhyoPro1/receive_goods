<?php

    function getAuth()
    {
        return auth()->guard()->user();
    }
