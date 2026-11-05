<?php

use Database\Factories\LetterCategoryFactory;
use Database\Factories\LetterFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\LetterCategory\Models\LetterCategoryModel;
use Tests\Concerns\AuthenticatesModuleAccess;
use Tests\TestCase;

class LetterCategoryModuleTest extends TestCase
{
    use AuthenticatesModuleAccess, RefreshDatabase;

    /** @test */
    public function letter_category_can_be_listed(): void
    {
        LetterCategoryFactory::new()->count(3)->create();

        $response = $this->getJson('/letter-categories');

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
    }

    /** @test */
    public function letter_category_can_be_created(): void
    {
        $response = $this->postJson('/letter-categories', [
            'code' => 'UND',
            'name' => 'Undangan',
            'description' => 'Surat undangan kegiatan',
            'is_active' => true,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('letter_categories', [
            'code' => 'UND',
            'name' => 'Undangan',
        ]);
    }

    /** @test */
    public function letter_category_can_be_shown(): void
    {
        $category = LetterCategoryFactory::new()->create();

        $response = $this->getJson('/letter-categories/'.$category->id);

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $category->id]);
    }

    /** @test */
    public function letter_category_can_be_updated(): void
    {
        $category = LetterCategoryFactory::new()->create();

        $response = $this->putJson('/letter-categories/'.$category->id, [
            'name' => 'Surat Undangan',
            'is_active' => false,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('letter_categories', [
            'id' => $category->id,
            'name' => 'Surat Undangan',
            'is_active' => false,
        ]);
    }

    /** @test */
    public function letter_category_can_be_deleted(): void
    {
        $category = LetterCategoryFactory::new()->create();

        $response = $this->deleteJson('/letter-categories/'.$category->id);

        $response->assertStatus(200);
        $this->assertSoftDeleted('letter_categories', ['id' => $category->id]);
    }

    /** @test */
    public function letter_category_used_by_letter_cannot_be_deleted(): void
    {
        $category = LetterCategoryFactory::new()->create();
        LetterFactory::new()->create(['category_id' => $category->id]);

        $response = $this->deleteJson('/letter-categories/'.$category->id);

        $response->assertStatus(422);
        $this->assertDatabaseHas('letter_categories', [
            'id' => $category->id,
            'deleted_at' => null,
        ]);
    }
}
