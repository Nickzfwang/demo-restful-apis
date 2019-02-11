<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    // 初始化資料庫
    protected function initDatabase()
    {
    	config([
            'database.default' => 'sqlite',
            'database.connections.sqlite' => [
                'driver'    => 'sqlite',
                'database'  => ':memory:',
                'prefix'    => '',
            ],
        ]);
        Artisan::call('migrate');
        Artisan::call('db:seed');
    }
    // 重置資料庫，確保每次測試皆互不影響
    protected function resetDatabase()
    {
    	Artisan::call('migrate:reset');
    }
}
