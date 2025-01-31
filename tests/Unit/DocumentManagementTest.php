<?php
namespace Tests\Feature;

use App\Models\{Document, User};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocumentManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_upload_document()
    {
        Storage::fake('private');

        $user = User::factory()->create();
        $file = UploadedFile::fake()->create('document.pdf', 1024);

        $response = $this->actingAs($user)
            ->post(route('documents.store'), [
                'title' => 'Test Document',
                'file' => $file
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('documents', ['title' => 'Test Document']);

        // Ensure file is stored
        Storage::disk('private')->assertExists("documents/{$file->hashName()}");
    }

    public function test_user_can_view_a_document()
    {
        $user = User::factory()->create();
        $document = Document::factory()->create();

        $this->actingAs($user)
            ->get(route('documents.show', $document))
            ->assertStatus(200);
    }

    public function test_user_can_update_a_document()
    {
        $user = User::factory()->create();
        $document = Document::factory()->create(['title' => 'Old Title']);

        $response = $this->actingAs($user)
            ->put(route('documents.update', $document), [
                'title' => 'Updated Title'
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('documents', ['title' => 'Updated Title']);
    }

    public function test_user_can_delete_a_document()
    {
        Storage::fake('private');

        $user = User::factory()->create();
        $document = Document::factory()->create();
        
        // Fake file to delete
        $file = UploadedFile::fake()->create('test.pdf', 1024);
        Storage::disk('private')->put("documents/{$file->hashName()}", 'content');

        $response = $this->actingAs($user)
            ->delete(route('documents.destroy', $document));

        $response->assertRedirect();
        $this->assertDatabaseMissing('documents', ['id' => $document->id]);

        // Ensure file is deleted
        Storage::disk('private')->assertMissing("documents/{$file->hashName()}");
    }
}
