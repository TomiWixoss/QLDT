<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        Role::create(['name' => 'Admin']);
        Role::create(['name' => 'Manager']);
        Role::create(['name' => 'Sales']);
        Role::create(['name' => 'Warehouse']);

        // Disable Vite for testing
        $this->withoutVite();
    }

    /**
     * Create a user with the specified role
     */
    protected function createUserWithRole(string $roleName, array $attributes = []): User
    {
        $role = Role::where('name', $roleName)->first();

        return User::create(array_merge([
            'role_id' => $role->id,
            'username' => fake()->unique()->userName(),
            'full_name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => 'password',
            'status' => 'active',
        ], $attributes));
    }

    // ============================================
    // Task 1: Admin Layout Tests (AC: 2, 6)
    // ============================================

    public function test_dashboard_displays_admin_layout_with_sidebar(): void
    {
        $user = $this->createUserWithRole('Admin', ['full_name' => 'Admin User']);

        $response = $this->actingAs($user)
            ->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Tact Admin');
        $response->assertSee('Dashboard');
        $response->assertSee('Đăng xuất');
    }

    public function test_dashboard_displays_user_info_in_sidebar(): void
    {
        $user = $this->createUserWithRole('Manager', ['full_name' => 'Nguyễn Văn Manager']);

        $response = $this->actingAs($user)
            ->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Nguyễn Văn Manager');
        $response->assertSee('Manager');
    }

    // ============================================
    // Task 2-4: Admin Login Tests (AC: 1, 2, 3, 4)
    // ============================================

    public function test_admin_login_page_displays_correctly(): void
    {
        $response = $this->get('/admin/login');

        $response->assertStatus(200);
        $response->assertSee('Tact Admin');
        $response->assertSee('Đăng nhập');
        $response->assertSee('Email');
        $response->assertSee('Mật khẩu');
    }

    public function test_staff_can_login_with_correct_credentials(): void
    {
        $user = $this->createUserWithRole('Admin', [
            'email' => 'admin@tact.vn',
            'password' => 'password123',
        ]);

        $response = $this->post('/admin/login', [
            'email' => 'admin@tact.vn',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_staff_cannot_login_with_incorrect_credentials(): void
    {
        $this->createUserWithRole('Admin', [
            'email' => 'admin@tact.vn',
            'password' => 'correctpassword',
        ]);

        $response = $this->post('/admin/login', [
            'email' => 'admin@tact.vn',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_failed_login_shows_vietnamese_error_message(): void
    {
        $this->createUserWithRole('Admin', [
            'email' => 'admin@tact.vn',
            'password' => 'correctpassword',
        ]);

        $response = $this->post('/admin/login', [
            'email' => 'admin@tact.vn',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors(['email']);
        $errors = session('errors');
        $this->assertStringContainsString('Email hoặc mật khẩu không đúng', $errors->first('email'));
    }

    public function test_login_is_rate_limited_after_5_attempts(): void
    {
        $this->createUserWithRole('Admin', [
            'email' => 'admin@tact.vn',
            'password' => 'correctpassword',
        ]);

        // Make 5 failed attempts
        for ($i = 0; $i < 5; $i++) {
            $this->post('/admin/login', [
                'email' => 'admin@tact.vn',
                'password' => 'wrongpassword',
            ]);
        }

        // 6th attempt should be rate limited
        $response = $this->post('/admin/login', [
            'email' => 'admin@tact.vn',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors(['email']);
        $errors = session('errors');
        $this->assertStringContainsString('Quá nhiều lần', $errors->first('email'));
    }

    public function test_staff_can_logout(): void
    {
        $user = $this->createUserWithRole('Admin');

        $response = $this->actingAs($user)
            ->post('/admin/logout');

        $response->assertRedirect('/admin/login');
        $this->assertGuest();
    }

    // ============================================
    // Task 5: Dashboard Tests (AC: 6)
    // ============================================

    public function test_dashboard_displays_welcome_message(): void
    {
        $user = $this->createUserWithRole('Admin', ['full_name' => 'Nguyễn Văn Admin']);

        $response = $this->actingAs($user)
            ->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Xin chào, Nguyễn Văn Admin');
    }

    public function test_dashboard_displays_user_role(): void
    {
        $user = $this->createUserWithRole('Sales', ['full_name' => 'Sales User']);

        $response = $this->actingAs($user)
            ->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Sales');
    }

    public function test_dashboard_displays_placeholder_stat_cards(): void
    {
        $user = $this->createUserWithRole('Admin');

        $response = $this->actingAs($user)
            ->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Đơn hàng hôm nay');
        $response->assertSee('Doanh thu hôm nay');
    }

    // ============================================
    // Task 6-8: Route Protection Tests (AC: 5)
    // ============================================

    public function test_unauthenticated_user_is_redirected_to_admin_login(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect('/admin/login');
    }

    public function test_unauthenticated_user_sees_flash_message(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect('/admin/login');
        $response->assertSessionHas('message', 'Vui lòng đăng nhập để tiếp tục');
    }

    public function test_authenticated_user_cannot_access_login_page(): void
    {
        $user = $this->createUserWithRole('Admin');

        $response = $this->actingAs($user)
            ->get('/admin/login');

        $response->assertRedirect('/admin/dashboard');
    }

    // ============================================
    // Task 9: User Model Helper Methods
    // ============================================

    public function test_user_has_role_method(): void
    {
        $user = $this->createUserWithRole('Admin');

        $this->assertTrue($user->hasRole('Admin'));
        $this->assertFalse($user->hasRole('Manager'));
    }

    public function test_user_has_any_role_method(): void
    {
        $user = $this->createUserWithRole('Manager');

        $this->assertTrue($user->hasAnyRole(['Admin', 'Manager']));
        $this->assertFalse($user->hasAnyRole(['Sales', 'Warehouse']));
    }
}
