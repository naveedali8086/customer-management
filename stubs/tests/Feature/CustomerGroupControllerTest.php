<?php

namespace Tests\Feature;

use App\Models\CustomerGroup;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CustomerGroupControllerTest extends TestCase
{
    // This trait resets the database after each test.
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Sanctum::actingAs(
            User::factory()->create()
        );
    }

    /** @test */
    public function it_can_create_a_customer_group()
    {
        $customerGroup = CustomerGroup::factory()->make()->toArray();

        $response = $this->postJson('/api/customer_groups', $customerGroup);

        $response
            ->assertStatus(201)
            ->assertJsonStructure(['data' => $this->getCustomerGroupAttributes()]);

        $this->assertEquals($customerGroup['name'], $response->json('data.name'));
    }

    /** @test */
    public function it_cannot_create_a_customer_group_with_invalid_data()
    {
        $customerGroup = CustomerGroup::factory()->make()->toArray();
        $customerGroup['name'] = ''; // emptying required field to create validation errors

        $response = $this->postJson('/api/customer_groups', $customerGroup);

        $response
            ->assertStatus(422) // Validation error
            ->assertJsonValidationErrors(['name']);

        // Because CustomerGroup name is unique, making sure that the customerGroup
        // with the specified name ($customerGroup['name']) does not exist in DB
        $this->assertDatabaseMissing('customer_groups', ['name' => $customerGroup['name']]);
    }

    /** @test */
    public function it_can_update_a_customer_group()
    {
        $customerGroup = CustomerGroup::factory()->create()->toArray();
        $updatedData = [...$customerGroup];
        $updatedData['name'] = fake()->name();

        $response = $this->putJson("/api/customer_groups/{$customerGroup['id']}", $updatedData);

        $response
            ->assertStatus(200)
            ->assertJsonStructure(['data' => $this->getCustomerGroupAttributes()]);

        $this->assertDatabaseHas('customer_groups', array_merge(['id' => $customerGroup['id']], $updatedData));
    }

    /** @test */
    public function it_cannot_update_a_customer_group_with_invalid_data()
    {
        $customerGroup = CustomerGroup::factory()->create()->toArray();
        $invalidData = [...$customerGroup];
        $invalidData['name'] = ''; // emptying required field to create validation errors

        $response = $this->putJson("/api/customer_groups/{$customerGroup['id']}", $invalidData);

        $response
            ->assertStatus(422) // Validation error
            ->assertJsonValidationErrors(['name']);

        $this->assertDatabaseMissing('customer_groups', $invalidData);
    }

    /** @test */
    public function it_can_soft_delete_a_customer_group()
    {
        $customerGroup = CustomerGroup::factory()->create();

        $response = $this->deleteJson("/api/customer_groups/$customerGroup->id");

        $response->assertStatus(204);

        $this->assertSoftDeleted('customer_groups', ['id' => $customerGroup->id]);
    }

    /** @test */
    public function it_can_get_a_single_customer_group()
    {
        $customerGroup = CustomerGroup::factory()->create()->toArray();

        $response = $this->getJson("/api/customer_groups/{$customerGroup['id']}");

        $response->assertStatus(200);

        $this->assertEquals($customerGroup, $response->json('data'));
    }

    /** @test */
    public function it_can_get_all_customer_groups()
    {
        CustomerGroup::factory()->count(10)->create();

        $response = $this->getJson('/api/customer_groups');

        $response
            ->assertStatus(200)
            ->assertJsonCount(10, 'data')
            ->assertJsonStructure([
                'data' => [
                    // '*' means there can be multiple items in the 'data' array.
                    '*' => $this->getCustomerGroupAttributes()
                ]
            ]);
    }

    private function getCustomerGroupAttributes(array $except = []): array
    {
        $attrs = [
            'id',
            'name',
            'description',
            'created_at',
            'updated_at'
        ];

        return array_diff($attrs, $except);
    }
}
