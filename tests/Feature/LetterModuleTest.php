<?php

use Database\Factories\LetterCategoryFactory;
use Database\Factories\LetterFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Letter\Models\LetterModel;
use Tests\TestCase;

class LetterModuleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function incoming_letters_can_be_listed(): void
    {
        $category = LetterCategoryFactory::new()->create();
        LetterFactory::new()->count(2)->create([
            'category_id' => $category->id,
            'direction' => 'incoming',
        ]);
        LetterFactory::new()->create([
            'category_id' => $category->id,
            'direction' => 'outgoing',
        ]);

        $response = $this->getJson('/letters/incoming');

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
    }

    /** @test */
    public function incoming_letter_can_be_created(): void
    {
        $category = LetterCategoryFactory::new()->create();

        $response = $this->postJson('/letters/incoming', [
            'category_id' => $category->id,
            'letter_number' => '001/UND/XI/2026',
            'subject' => 'Undangan Rapat Koordinasi',
            'sender' => 'Kelurahan Sentral',
            'letter_date' => '2026-11-01',
            'received_date' => '2026-11-02',
            'notes' => 'Rapat di aula kelurahan.',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('letters', [
            'letter_number' => '001/UND/XI/2026',
            'direction' => 'incoming',
        ]);
    }

    /** @test */
    public function outgoing_letter_can_be_created(): void
    {
        $category = LetterCategoryFactory::new()->create();

        $response = $this->postJson('/letters/outgoing', [
            'category_id' => $category->id,
            'letter_number' => '002/PMH/XI/2026',
            'subject' => 'Permohonan Bantuan Dana',
            'recipient' => 'Pemerintah Kota',
            'letter_date' => '2026-11-03',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('letters', [
            'letter_number' => '002/PMH/XI/2026',
            'direction' => 'outgoing',
        ]);
    }

    /** @test */
    public function letter_can_be_shown(): void
    {
        $letter = LetterFactory::new()->create(['direction' => 'incoming']);

        $response = $this->getJson('/letters/incoming/'.$letter->id);

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $letter->id]);
    }

    /** @test */
    public function letter_can_be_updated(): void
    {
        $letter = LetterFactory::new()->create(['direction' => 'incoming']);

        $response = $this->putJson('/letters/incoming/'.$letter->id, [
            'subject' => 'Perihal Surat Diperbarui',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('letters', [
            'id' => $letter->id,
            'subject' => 'Perihal Surat Diperbarui',
        ]);
    }

    /** @test */
    public function letter_can_be_deleted(): void
    {
        $letter = LetterFactory::new()->create(['direction' => 'incoming']);

        $response = $this->deleteJson('/letters/incoming/'.$letter->id);

        $response->assertStatus(200);
        $this->assertSoftDeleted('letters', ['id' => $letter->id]);
    }
}
