<?php

namespace Pro1\Changelog\Models;

if (env('PORTAL_ID') == 2) {
    class BaseRole extends \App\Models\Role {}
} else {
    class BaseRole extends \Spatie\Permission\Models\Role {}
}
