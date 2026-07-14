<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use RuntimeException;

class ExecutionTimer
{
    protected float $start;
    protected int $timeout;

    public function __construct(int $timeoutSeconds)
    {
        $this->start = microtime(true);
        $this->timeout = $timeoutSeconds;
    }

    public function check(?string $message = null): void
    {
        // dd((microtime(true) - $this->start));
        $isTimedOut = (microtime(true) - $this->start) > $this->timeout;
        // $isTimedOut = true;
        if ($isTimedOut) {
            Log::error('Processing took too long. Please try again.');
            throw new RuntimeException(
                $message ?? "Operation exceeded {$this->timeout} seconds."
            );
        }
    }

    public function elapsed(): float
    {
        return microtime(true) - $this->start;
    }
}