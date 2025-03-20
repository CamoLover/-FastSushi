<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\EncryptCookies as Middleware;

class EncryptCookies extends Middleware
{
    /**
     * The names of the cookies that should not be encrypted.
     *
     * @var array<int, string>
     */
    protected $except = [
        'panier',
        'test_cookie',
        'test_cookie_manual',
        'test_browser_cookie'
    ];
} 