<?php

namespace Tests\Feature\Auth;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_customers_can_register(): void
    {
        $response = $this->post('/register', [
            'full_name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '0901234567',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertAuthenticated('customer');
        $response->assertRedirect(route('home'));

        $this->assertDatabaseHas('customers', [
            'email' => 'test@example.com',
            'full_name' => 'Test User',
            'phone' => '0901234567',
            'status' => 'active',
            'points' => 0,
        ]);
    }

    public function test_password_is_hashed(): void
    {
        $this->post('/register', [
            'full_name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '0901234567',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $customer = Customer::where('email', 'test@example.com')->first();

        $this->assertNotEquals('password123', $customer->password);
        $this->assertTrue(password_verify('password123', $customer->password));
    }

    public function test_duplicate_email_shows_error(): void
    {
        Customer::factory()->create(['email' => 'test@example.com']);

        $response = $this->post('/register', [
            'full_name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '0901234567',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest('customer');
    }

    public function test_short_password_shows_error(): void
    {
        $response = $this->post('/register', [
            'full_name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '0901234567',
            'password' => 'short',
            'password_confirmation' => 'short',
        ]);

        $response->assertSessionHasErrors(['password']);
        $this->assertGuest('customer');
    }

    public function test_mismatched_passwords_shows_error(): void
    {
        $response = $this->post('/register', [
            'full_name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '0901234567',
            'password' => 'password123',
            'password_confirmation' => 'different123',
        ]);

        $response->assertSessionHasErrors(['password']);
        $this->assertGuest('customer');
    }

    public function test_invalid_phone_shows_error(): void
    {
        $response = $this->post('/register', [
            'full_name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '123456',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['phone']);
        $this->assertGuest('customer');
    }

    public function test_form_retains_input_on_error_except_password(): void
    {
        Customer::factory()->create(['email' => 'existing@example.com']);

        $response = $this->from('/register')->post('/register', [
            'full_name' => 'Test User',
            'email' => 'existing@example.com',
            'phone' => '0901234567',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertEquals('Test User', session('_old_input.full_name'));
        $this->assertEquals('existing@example.com', session('_old_input.email'));
        $this->assertEquals('0901234567', session('_old_input.phone'));
        $this->assertNull(session('_old_input.password'));
    }
}
