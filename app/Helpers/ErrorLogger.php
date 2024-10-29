<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class ErrorLogger
{
    public static function logError($message)
    {
        // Log error ke laravel.log atau log file lainnya sesuai konfigurasi
        Log::error($message);
    }
}
