<?php

namespace Tests\Feature\Models;

use App\Models\PetBreed;
use App\Models\PetType;
use App\Models\Pet;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PetBreedTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_pet_breed()
    {
        $type = PetType::factory()->create();
        
        $breedData = [
            'pet_type_id' => $type->id,
            'name' => 'Golden Retriever',
            'description' => 'Friendly family dog',
            'characteristics' => [
                'temperament' => 'Friendly',
                'size' => 'Large',
                'life_span' => '10-12 years'
            ],
            'care_instructions' => [
                'diet' => 'High-quality dog food',
                'exercise' => 'Daily walks'
            ],
            'is_active' => true
        ];

        $breed = PetBreed::create($breedData);

        $this->assertDatabaseHas('pet_breeds', [
            'id' => $breed->id,
            'name' => 'Golden Retriever'
        ]);
    }

    public function test_breed_belongs_to_type()
    {
        $breed = PetBreed::factory()->create();
        
        $this->assertInstanceOf(PetType::class, $breed->type);
    }

    public function test_breed_has_many_pets()
    {
        $breed = PetBreed::factory()->create();
        Pet::factory()->count(3)->create(['pet_breed_id' => $breed->id]);

        $this->assertCount(3, $breed->pets);
        $this->assertInstanceOf(Pet::class, $breed->pets->first());
    }

    public function test_can_filter_active_breeds()
    {
        PetBreed::factory()->create(['is_active' => true]);
        PetBreed::factory()->create(['is_active' => false]);

        $activeBreeds = PetBreed::active()->get();

        $this->assertEquals(1, $activeBreeds->count());
    }

    public function test_can_filter_by_type()
    {
        $type = PetType::factory()->create();
        PetBreed::factory()->create(['pet_type_id' => $type->id]);
        PetBreed::factory()->create();

        $filteredBreeds = PetBreed::byType($type->id)->get();

        $this->assertEquals(1, $filteredBreeds->count());
    }

    public function test_breed_name_must_be_unique()
    {
        $breed1 = PetBreed::factory()->create(['name' => 'Golden Retriever']);

        $this->expectException(\Illuminate\Database\QueryException::class);

        PetBreed::factory()->create(['name' => 'Golden Retriever']);
    }

    public function test_json_columns_are_arrays()
    {
        $breed = PetBreed::factory()->create();

        $this->assertIsArray($breed->characteristics);
        $this->assertIsArray($breed->care_instructions);
    }
}
