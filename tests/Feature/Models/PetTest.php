<?php

namespace Tests\Feature\Models;

use App\Models\Pet;
use App\Models\User;
use App\Models\PetType;
use App\Models\PetBreed;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PetTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_pet()
    {
        $user = User::factory()->create();
        $type = PetType::factory()->create();
        $breed = PetBreed::factory()->create(['pet_type_id' => $type->id]);

        $petData = [
            'user_id' => $user->id,
            'pet_type_id' => $type->id,
            'pet_breed_id' => $breed->id,
            'name' => 'Buddy',
            'description' => 'A friendly dog',
            'age' => 3,
            'size' => 'medium',
            'daily_rate' => 25.00,
            'is_available' => true,
            'care_instructions' => ['feeding' => 'Twice a day'],
            'photos' => ['http://example.com/photo.jpg']
        ];

        $pet = Pet::create($petData);

        $this->assertDatabaseHas('pets', [
            'id' => $pet->id,
            'name' => 'Buddy'
        ]);

        $this->assertEquals($user->id, $pet->user_id);
        $this->assertEquals($type->id, $pet->pet_type_id);
        $this->assertEquals($breed->id, $pet->pet_breed_id);
    }

    public function test_pet_belongs_to_user()
    {
        $pet = Pet::factory()->create();
        $this->assertInstanceOf(User::class, $pet->user);
    }

    public function test_pet_belongs_to_type()
    {
        $pet = Pet::factory()->create();
        $this->assertInstanceOf(PetType::class, $pet->type);
    }

    public function test_pet_belongs_to_breed()
    {
        $pet = Pet::factory()->create();
        $this->assertInstanceOf(PetBreed::class, $pet->breed);
    }

    public function test_can_filter_available_pets()
    {
        Pet::factory()->create(['is_available' => true]);
        Pet::factory()->create(['is_available' => false]);

        $availablePets = Pet::available()->get();

        $this->assertEquals(1, $availablePets->count());
    }

    public function test_can_filter_by_type()
    {
        $type = PetType::factory()->create();
        Pet::factory()->create(['pet_type_id' => $type->id]);
        Pet::factory()->create();

        $filteredPets = Pet::byType($type->id)->get();

        $this->assertEquals(1, $filteredPets->count());
    }

    public function test_can_filter_by_price_range()
    {
        Pet::factory()->create(['daily_rate' => 20]);
        Pet::factory()->create(['daily_rate' => 50]);
        Pet::factory()->create(['daily_rate' => 100]);

        $filteredPets = Pet::byPriceRange(40, 60)->get();

        $this->assertEquals(1, $filteredPets->count());
        $this->assertEquals(50, $filteredPets->first()->daily_rate);
    }
}
