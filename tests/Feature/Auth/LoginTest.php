<?php

namespace Tests\Feature\Auth;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        // Clear all rate limiters after each test
        RateLimiter::clear('login');
        parent::tearDown();
    }

    #[Test]
    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    #[Test]
    public function test_customers_can_login_with_valid_credentials(): void
    {
        $customer = Customer::factory()->create([
            'password' => 'password123',
        ]);

        $response = $this->post('/login', [
            'email' => $customer->email,
            'password' => 'password123',
        ]);

        $this->assertAuthenticated('customer');
        $response->assertRedirect(route('home'));
        $response->assertSessionHas('success', 'Đăng nhập thành công');
    }

    #[Test]
    public function test_customers_cannot_login_with_invalid_password(): void
    {
        $customer = Customer::factory()->create([
            'password' => 'password123',
        ]);

        $response = $this->post('/login', [
            'email' => $customer->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest('customer');
        $response->assertSessionHasErrors(['email']);
    }

    #[Test]
    public function test_customers_cannot_login_with_nonexistent_email(): void
    {
        $response = $this->post('/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'password123',
        ]);

        $this->assertGuest('customer');
        $response->assertSessionHasErrors(['email']);
    }

    #[Test]
    public function test_login_is_throttled_after_5_failed_attempts(): void
    {
        $customer = Customer::factory()->create();

        // Make 5 failed attempts
        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', [
                'email' => $customer->email,
                'password' => 'wrong-password',
            ]);
        }

        // 6th attempt should be throttled
        $response = $this->post('/login', [
            'email' => $customer->email,
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertStringContainsString(
            'Quá nhiều lần đăng nhập sai',
            session('errors')->get('email')[0]
        );
    }

    #[Test]
    public function test_customers_can_logout(): void
    {
        $customer = Customer::factory()->create();

        $this->actingAs($customer, 'customer');

        $response = $this->post('/logout');

        $this->assertGuest('customer');
        $response->assertRedirect(route('home'));
        $response->assertSessionHas('success', 'Đã đăng xuất thành công');
    }

    #[Test]
    public function test_remember_me_creates_remember_cookie(): void
    {
        $customer = Customer::factory()->create([
            'password' => 'password123',
        ]);

        $response = $this->post('/login', [
            'email' => $customer->email,
            'password' => 'password123',
            'remember' => true,
        ]);

        $this->assertAuthenticated('customer');
        // Check that remember cookie is set
        $response->assertCookie(
            Auth::guard('customer')->getRecallerName()
        );
    }

    #[Test]
    public function test_login_validation_requires_email(): void
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    #[Test]
    public function test_login_validation_requires_password(): void
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    #[Test]
    public function test_guests_cannot_access_logout(): void
    {
        $response = $this->post('/logout');

        $response->assertRedirect('/login');
    }

    #[Test]
    public function test_authenticated_customers_cannot_access_login_page(): void
    {
        $customer = Customer::factory()->create();

        $this->actingAs($customer, 'customer');

        $response = $this->get('/login');

        $response->assertRedirect(route('home'));
    }

    #[Test]
    public function test_login_form_contains_csrf_token(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('name="_token"', false);
    }
}
