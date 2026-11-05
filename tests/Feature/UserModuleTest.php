<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\AuthenticatesModuleAccess;
use Tests\TestCase;

class UserModuleTest extends TestCase
{
    use AuthenticatesModuleAccess, RefreshDatabase;

    /** @test */
    public function user_can_be_listed(): void
    {
        User::factory()->count(3)->create();

        $response = $this->getJson('/users');

        $response->assertStatus(200);
        $response->assertJsonCount(User::count(), 'data');
    }

    /** @test */
    public function user_can_be_created(): void
    {
        $response = $this->postJson('/users', [
            'name' => 'Pengguna Baru',
            'email' => 'baru@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', ['email' => 'baru@example.com']);
    }

    /** @test */
    public function user_can_be_shown(): void
    {
        $user = User::factory()->create();

        $response = $this->getJson('/users/'.$user->id);

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $user->id]);
    }

    /** @test */
    public function user_can_be_updated(): void
    {
        $user = User::factory()->create();

        $response = $this->putJson('/users/'.$user->id, [
            'name' => 'Nama Diperbarui',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Nama Diperbarui',
        ]);
    }

    /** @test */
    public function user_can_be_deleted(): void
    {
        $user = User::factory()->create();

        $response = $this->deleteJson('/users/'.$user->id);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
