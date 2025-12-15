<?php

namespace Tests\Feature\Auth;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use Tests\TestCase;

class GoogleAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function mockSocialiteUser(array $data = []): void
    {
        $socialiteUser = Mockery::mock(SocialiteUser::class);
        $socialiteUser->shouldReceive('getId')->andReturn($data['id'] ?? '123456789');
        $socialiteUser->shouldReceive('getEmail')->andReturn($data['email'] ?? 'test@gmail.com');
        $socialiteUser->shouldReceive('getName')->andReturn($data['name'] ?? 'Test User');

        $driver = Mockery::mock();
        $driver->shouldReceive('user')->andReturn($socialiteUser);

        Socialite::shouldReceive('driver')
            ->with('google')
            ->andReturn($driver);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function google_redirect_redirects_to_google(): void
    {
        // Mock the redirect
        $driver = Mockery::mock();
        $driver->shouldReceive('redirect')
            ->once()
            ->andReturn(redirect('https://accounts.google.com/o/oauth2/auth'));

        Socialite::shouldReceive('driver')
            ->with('google')
            ->andReturn($driver);

        $response = $this->get('/auth/google');

        $response->assertRedirect();
    }

    /** @test */
    public function first_time_google_registration_creates_customer(): void
    {
        $this->mockSocialiteUser([
            'id' => '123456789',
            'email' => 'newuser@gmail.com',
            'name' => 'New User',
        ]);

        $response = $this->get('/auth/google/callback');

        $this->assertDatabaseHas('customers', [
            'email' => 'newuser@gmail.com',
            'google_id' => '123456789',
            'full_name' => 'New User',
        ]);

        $customer = Customer::where('email', 'newuser@gmail.com')->first();
        $this->assertNull($customer->getAttributes()['password']);
        $this->assertAuthenticated('customer');
        $response->assertRedirect(route('password.set'));
        $response->assertSessionHas('success');
    }


    /** @test */
    public function returning_google_user_is_logged_in(): void
    {
        $customer = Customer::factory()->create([
            'google_id' => '123456789',
            'email' => 'existing@gmail.com',
            'password' => 'hashedpassword',
        ]);

        $this->mockSocialiteUser([
            'id' => '123456789',
            'email' => 'existing@gmail.com',
            'name' => 'Existing User',
        ]);

        $response = $this->get('/auth/google/callback');

        $this->assertAuthenticated('customer');
        $response->assertRedirect(route('home'));
        $response->assertSessionHas('success', 'Đăng nhập thành công');
    }

    /** @test */
    public function email_conflict_links_google_account(): void
    {
        $customer = Customer::factory()->create([
            'email' => 'existing@gmail.com',
            'google_id' => null,
            'password' => 'password123',
        ]);

        $this->mockSocialiteUser([
            'id' => '123456789',
            'email' => 'existing@gmail.com',
            'name' => 'Existing User',
        ]);

        $response = $this->get('/auth/google/callback');

        $customer->refresh();
        $this->assertEquals('123456789', $customer->google_id);
        $this->assertAuthenticated('customer');
        $response->assertRedirect(route('home'));
        $response->assertSessionHas('success', 'Tài khoản đã được liên kết với Google');
    }

    /** @test */
    public function google_user_without_password_cannot_email_login(): void
    {
        Customer::factory()->create([
            'email' => 'googleuser@gmail.com',
            'google_id' => '123456789',
            'password' => null,
        ]);

        $response = $this->post('/login', [
            'email' => 'googleuser@gmail.com',
            'password' => 'anypassword',
        ]);

        $this->assertGuest('customer');
        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function set_password_page_shows_for_google_user_without_password(): void
    {
        $customer = Customer::factory()->create([
            'google_id' => '123456789',
            'password' => null,
        ]);

        $this->actingAs($customer, 'customer');

        $response = $this->get('/password/set');

        $response->assertStatus(200);
        $response->assertViewIs('auth.set-password');
    }

    /** @test */
    public function set_password_works_for_google_user(): void
    {
        $customer = Customer::factory()->create([
            'google_id' => '123456789',
            'password' => null,
        ]);

        $this->actingAs($customer, 'customer');

        $response = $this->post('/password/set', [
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $customer->refresh();
        $this->assertNotNull($customer->getAttributes()['password']);
        $response->assertRedirect(route('home'));
        $response->assertSessionHas('success', 'Đặt mật khẩu thành công');
    }

    /** @test */
    public function set_password_page_redirects_if_password_exists(): void
    {
        $customer = Customer::factory()->create([
            'password' => 'existingpassword',
        ]);

        $this->actingAs($customer, 'customer');

        $response = $this->get('/password/set');

        $response->assertRedirect(route('home'));
    }

    /** @test */
    public function set_password_validates_minimum_length(): void
    {
        $customer = Customer::factory()->create([
            'google_id' => '123456789',
            'password' => null,
        ]);

        $this->actingAs($customer, 'customer');

        $response = $this->post('/password/set', [
            'password' => 'short',
            'password_confirmation' => 'short',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    /** @test */
    public function set_password_validates_confirmation(): void
    {
        $customer = Customer::factory()->create([
            'google_id' => '123456789',
            'password' => null,
        ]);

        $this->actingAs($customer, 'customer');

        $response = $this->post('/password/set', [
            'password' => 'newpassword123',
            'password_confirmation' => 'differentpassword',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    /** @test */
    public function google_oauth_routes_require_guest(): void
    {
        $customer = Customer::factory()->create();

        $this->actingAs($customer, 'customer');

        $response = $this->get('/auth/google');

        $response->assertRedirect(route('home'));
    }
}
