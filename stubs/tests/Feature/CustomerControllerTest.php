<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CustomerControllerTest extends TestCase
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
    public function it_can_create_a_customer()
    {
        $customer = Customer::factory()->make()->toArray();

        $contact = Contact::factory()->make();

        $response = $this->postJson(
            '/api/customers',
            array_merge($customer, [
                'contact_channel' => $contact->channel,
                'contact_channel_value' => $contact->channel_value,
            ])
        );

        $response
            ->assertStatus(201)
            ->assertJsonStructure(['data' => $this->getCustomerAttributes()]);

        // making sure customer exits in DB
        $this->assertDatabaseHas('customers', ['id' => $response->json('data.id')]);

        // making sure a contacts (associated with above customer) also exits in DB
        $this->assertDatabaseHas('contacts', [
            'contactable_id' => $response->json('data.id'),
            'contactable_type' => Customer::class
        ]);
    }

    /** @test */
    public function it_cannot_create_a_customer_with_invalid_data()
    {
        $customer = Customer::factory()->make()->toArray();
        $customer['first_name'] = ''; // emptying required field to create validation errors

        $response = $this->postJson('/api/customers', $customer);

        $response
            ->assertStatus(422) // Validation error
            ->assertJsonValidationErrors(['first_name']);

        $this->assertDatabaseMissing('customers', $customer);
    }

    /** @test */
    public function it_cannot_create_a_customer_with_duplicate_contact()
    {
        // creating a customer and a contact associated with it
        $customer = Customer::factory()
            ->has(Contact::factory())
            ->create();

        $contact = $customer
            ->contacts()
            ->select(['id', 'channel', 'channel_value'])
            ->first();

        $customerToBeCreated = Customer::factory()->make()->toArray();

        $contactToBeCreated = Contact::factory()->make()->toArray();
        // passing existing contact's values to a "$contactToBeCreated" to raise validation error
        // i.e. If an email/mobile already exist in contacts table against any customer
        // then a new customer can not be created
        $contactToBeCreated['channel'] = $contact->channel;
        $contactToBeCreated['channel_value'] = $contact->channel_value;

        $response = $this->postJson('/api/customers', array_merge(
            $customerToBeCreated,
            $contactToBeCreated
        ));

        $response
            ->assertStatus(422) // Validation error
            ->assertJsonValidationErrors(['contact_channel_value']);

        // the DB should have only 1 customer as 2nd was not created due to validation errors
        $this->assertEquals(1, Customer::count());
    }

    /** @test */
    public function it_can_update_a_customer()
    {
        $customer = Customer::factory()->create()->toArray();
        $updatedData = [...$customer];
        $updatedData['first_name'] = fake()->firstName();
        $updatedData['last_name'] = fake()->lastName();

        $response = $this->putJson("/api/customers/{$customer['id']}", $updatedData);

        $response
            ->assertStatus(200)
            ->assertJsonStructure(['data' => $this->getCustomerAttributes()]);

        $this->assertDatabaseHas('customers', array_merge(['id' => $customer['id']], $updatedData));
    }

    /** @test */
    public function it_cannot_update_a_customer_with_invalid_data()
    {
        $customer = Customer::factory()->create()->toArray();
        $invalidData = [...$customer];
        $invalidData['first_name'] = ''; // emptying required field to create validation errors

        $response = $this->putJson("/api/customers/{$customer['id']}", $invalidData);

        $response
            ->assertStatus(422) // Validation error
            ->assertJsonValidationErrors(['first_name']);

        $this->assertDatabaseMissing('customers', $invalidData);
    }

    /** @test */
    public function it_can_soft_delete_a_customer()
    {
        $customer = Customer::factory()
            ->has(Contact::factory()->count(3))
            ->create();

        $contacts = $customer->contacts;

        $response = $this->deleteJson("/api/customers/$customer->id");

        $response->assertStatus(204);

        $this->assertSoftDeleted('customers', ['id' => $customer->id]);

        // making sure the contacts associated with the deleted customer has also been deleted
        foreach ($contacts as $contact) {
            $this->assertSoftDeleted('contacts', ['id' => $contact->id]);
        }
    }

    /** @test */
    public function it_can_get_a_single_customer()
    {
        $customer = Customer::factory()->create()->toArray();

        $response = $this->getJson("/api/customers/{$customer['id']}");

        $response->assertStatus(200);

        $this->assertEquals($customer, $response->json('data'));
    }

    /** @test */
    public function it_can_get_all_customers()
    {
        Customer::factory()->count(10)->create();

        $response = $this->getJson('/api/customers');

        $response
            ->assertStatus(200)
            ->assertJsonCount(10, 'data')
            ->assertJsonStructure([
                'data' => [
                    // '*' means there can be multiple items in the 'data' array.
                    '*' => $this->getCustomerAttributes()
                ]
            ]);
    }

    private function getCustomerAttributes(array $except = []): array
    {
        $attrs = [
            'id',
            'first_name',
            'middle_name',
            'last_name',
            'customer_code',
            'enable_notification',
            'date_of_birth',
            'gender',
            'gender_other',
            'note',
            'created_at',
            'updated_at',
        ];

        return array_diff($attrs, $except);
    }
}
