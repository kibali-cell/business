<?php

namespace Database\Factories;

use App\Models\Document;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentFactory extends Factory
{
    protected $model = Document::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'folder_id' => null,
            'uploaded_by' => \App\Models\User::factory(),
            'status' => $this->faker->randomElement(Document::statuses())
        ];
    }
}