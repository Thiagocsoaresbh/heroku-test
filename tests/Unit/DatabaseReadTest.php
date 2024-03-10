<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DatabaseReadTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function users_table_exists()
    {
        $this->assertTrue(Schema::hasTable('users'));
    }

    /** @test */
    public function accounts_table_exists()
    {
        $this->assertTrue(Schema::hasTable('accounts'));
    }

    /** @test */
    public function transactions_table_exists()
    {
        $this->assertTrue(Schema::hasTable('transactions'));
    }

    /** @test */
    public function checks_table_exists()
    {
        $this->assertTrue(Schema::hasTable('checks'));
    }
}
