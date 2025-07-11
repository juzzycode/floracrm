<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;

class Filters extends BaseConfig
{
    public $aliases = [
        'csrf'     => CSRF::class,
        'toolbar'  => DebugToolbar::class,
        'honeypot' => Honeypot::class,
        'auth'     => \App\Filters\AuthFilter::class,
        'admin'    => \App\Filters\AdminFilter::class,
    ];

    public $globals = [
        'before' => [
            // 'honeypot',
            'csrf',
        ],
        'after' => [
            'toolbar',
            // 'honeypot',
        ],
    ];

    public $methods = [];

    public $filters = [];
}