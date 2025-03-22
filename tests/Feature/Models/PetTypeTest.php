<?php

namespace Tests\Feature\Models;

use App\Models\PetType;
use App\Models\PetBreed;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PetTypeTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_pet_type()
    {
        $typeData = [
            'name' => 'Dog',
            'description' => 'Mans best friend',
            'icon' => 'fa-dog',
            'is_active' => true
        ];

        $type = PetType::create($typeData);

        $this->assertDatabaseHas('pet_types', [
            'id' => $type->id,
            'name' => 'Dog'
        ]);
    }

    public function test_pet_type_has_many_breeds()
    {
        $type = PetType::factory()->create();
        PetBreed::factory()->count(3)->create(['pet_type_id' => $type->id]);

        $this->assertCount(3, $type->breeds);
        $this->assertInstanceOf(PetBreed::class, $type->breeds->first());
    }

    public function test_can_filter_active_types()
    {
        PetType::factory()->create(['is_active' => true]);
        PetType::factory()->create(['is_active' => false]);

        $activeTypes = PetType::active()->get();

        $this->assertEquals(1, $activeTypes->count());
    }

    public function test_type_name_must_be_unique()
    {
        $type1 = PetType::factory()->create(['name' => 'Dog']);

        $this->expectException(\Illuminate\Database\QueryException::class);

        PetType::factory()->create(['name' => 'Dog']);
    }
}
