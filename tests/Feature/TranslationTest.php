<?php

namespace Tests\Feature;

use App\Models\Translation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TranslationTest extends TestCase
{
    use RefreshDatabase;

    protected $headers;

    protected function setUp(): void
    {
        parent::setUp();
        $this->headers = [
            'Authorization' => 'Bearer ' . env('API_AUTH_TOKEN', 'vSI4ng4s18LMGhYTBsYOyLaBqLnNESpoONoEo4YWG8Q'),
            'Accept' => 'application/json',
        ];
    }
    
    public function test_can_create_translation()
    {
        $payload = [
            'locale' => 'en',
            'translation_key' => 'welcome',
            'content' => 'Welcome!',
            'tags' => ['web', 'mobile']
        ];

        $response = $this->postJson('/api/translations', $payload, $this->headers);

        $response->assertStatus(201)
            ->assertJsonFragment(['translation_key' => 'welcome'])
            ->assertJsonStructure(['id', 'locale', 'translation_key', 'content', 'tags']);
    }

    public function test_can_update_translation()
    {
        $translation = Translation::factory()->create([
            'locale' => 'en',
            'translation_key' => 'greeting',
            'content' => 'Hello'
        ]);

        $payload = [
            'content' => 'Hi there!',
            'tags' => ['desktop']
        ];

        $response = $this->putJson("/api/translations/{$translation->id}", $payload, $this->headers);
        $response->assertStatus(200)
            ->assertJsonFragment(['content' => 'Hi there!'])
            ->assertJsonStructure(['id', 'locale', 'translation_key', 'content', 'tags']);
    }

    public function test_can_search_translation_by_tag()
    {
        $translation = Translation::factory()->create([
            'locale' => 'en',
            'translation_key' => 'farewell',
            'content' => 'Goodbye!'
        ]);
        $translation->tags()->create(['name' => 'mobile']);

        $response = $this->getJson('/api/translations?tag=mobile', $this->headers);
        $response->assertStatus(200)
            ->assertJsonFragment(['translation_key' => 'farewell']);
    }

    public function test_export_returns_translations()
    {
        // Create sample records.
        Translation::factory()->count(10)->create();
    
        // Use get() instead of getJson() because the endpoint streams the response.
        $response = $this->get('/api/translations/export', $this->headers);
    
        // Assert HTTP status is 200.
        $response->assertStatus(200);
    
        // Capture the streamed output.
        ob_start();
        $response->sendContent();
        $content = ob_get_clean();
    
        // Decode the JSON content.
        $data = json_decode($content, true);
    
        // Basic assertions on the decoded data.
        $this->assertIsArray($data, 'Exported content is not an array');
    
        // If there is at least one record, verify its structure.
        if (count($data) > 0) {
            $record = $data[0];
            $this->assertArrayHasKey('id', $record);
            $this->assertArrayHasKey('locale', $record);
            $this->assertArrayHasKey('translation_key', $record);
            $this->assertArrayHasKey('content', $record);
            $this->assertArrayHasKey('tags', $record);
        }
    }
}
