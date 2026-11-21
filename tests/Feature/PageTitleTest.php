<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\AuthenticatesModuleAccess;
use Tests\TestCase;

class PageTitleTest extends TestCase
{
    use AuthenticatesModuleAccess, RefreshDatabase;

    /** @test */
    public function dashboard_page_title_uses_the_page_name(): void
    {
        $this->get('/dashboard')
            ->assertOk()
            ->assertSee('<title>Dashboard | '.config('app.name').'</title>', false);
    }

    /** @test */
    public function module_page_title_uses_the_page_name(): void
    {
        $this->get('/members')
            ->assertOk()
            ->assertSee('<title>Member | '.config('app.name').'</title>', false);
    }

    /** @test */
    public function module_create_page_title_uses_the_page_name(): void
    {
        $this->get('/members/create')
            ->assertOk()
            ->assertSee('<title>Tambah Anggota | '.config('app.name').'</title>', false);
    }
}
