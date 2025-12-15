<?php

namespace Tests\Feature\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $manager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);

        $adminRole = Role::where('name', 'Admin')->first();
        $managerRole = Role::where('name', 'Manager')->first();

        $this->admin = User::factory()->create([
            'role_id' => $adminRole->id,
            'username' => 'admin_test',
            'email' => 'admin@test.com',
            'status' => 'active',
        ]);

        $this->manager = User::factory()->create([
            'role_id' => $managerRole->id,
            'username' => 'manager_test',
            'email' => 'manager@test.com',
            'status' => 'active',
        ]);
    }

    // AC1: View User List
    public function test_admin_can_view_user_list(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.users.index'));
        $response->assertStatus(200);
        $response->assertSee('Quản lý người dùng');
        $response->assertSee('Tạo người dùng mới');
    }

    public function test_user_list_shows_pagination(): void
    {
        // Create 15 users to trigger pagination
        User::factory()->count(15)->create(['role_id' => $this->admin->role_id]);

        $response = $this->actingAs($this->admin)->get(route('admin.users.index'));
        $response->assertStatus(200);
    }

    // AC2: Create New User
    public function test_admin_can_view_create_user_form(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.users.create'));
        $response->assertStatus(200);
        $response->assertSee('Tạo người dùng mới');
    }

    public function test_admin_can_create_user(): void
    {
        $salesRole = Role::where('name', 'Sales')->first();

        $response = $this->actingAs($this->admin)->post(route('admin.users.store'), [
            'username' => 'newuser',
            'email' => 'newuser@test.com',
            'full_name' => 'New User',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role_id' => $salesRole->id,
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success', 'Tạo người dùng thành công');
        $this->assertDatabaseHas('users', [
            'username' => 'newuser',
            'email' => 'newuser@test.com',
            'full_name' => 'New User',
        ]);
    }

    public function test_password_is_hashed_on_create(): void
    {
        $salesRole = Role::where('name', 'Sales')->first();

        $this->actingAs($this->admin)->post(route('admin.users.store'), [
            'username' => 'hashtest',
            'email' => 'hashtest@test.com',
            'full_name' => 'Hash Test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role_id' => $salesRole->id,
        ]);

        $user = User::where('username', 'hashtest')->first();
        $this->assertNotEquals('password123', $user->password);
        $this->assertTrue(password_verify('password123', $user->password));
    }

    // AC3: Update User Information
    public function test_admin_can_view_edit_user_form(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.users.edit', $this->manager));
        $response->assertStatus(200);
        $response->assertSee($this->manager->full_name);
    }

    public function test_admin_can_update_user(): void
    {
        $user = User::factory()->create([
            'username' => 'olduser',
            'role_id' => $this->admin->role_id,
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.users.update', $user), [
            'username' => 'updateduser',
            'email' => $user->email,
            'full_name' => 'Updated Name',
            'role_id' => $user->role_id,
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success', 'Cập nhật người dùng thành công');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'username' => 'updateduser',
            'full_name' => 'Updated Name',
        ]);
    }

    public function test_admin_can_update_user_password(): void
    {
        $user = User::factory()->create(['role_id' => $this->admin->role_id]);

        $this->actingAs($this->admin)->put(route('admin.users.update', $user), [
            'username' => $user->username,
            'email' => $user->email,
            'full_name' => $user->full_name,
            'role_id' => $user->role_id,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $user->refresh();
        $this->assertTrue(password_verify('newpassword123', $user->password));
    }

    // AC4: Deactivate User
    public function test_admin_can_deactivate_user(): void
    {
        $user = User::factory()->create([
            'status' => 'active',
            'role_id' => $this->admin->role_id,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.users.destroy', $user));

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success', 'Đã vô hiệu hóa người dùng');
        $this->assertDatabaseHas('users', ['id' => $user->id, 'status' => 'inactive']);
    }

    // AC5: Reactivate User
    public function test_admin_can_reactivate_user(): void
    {
        $user = User::factory()->create([
            'status' => 'inactive',
            'role_id' => $this->admin->role_id,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.users.destroy', $user));

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success', 'Đã kích hoạt người dùng');
        $this->assertDatabaseHas('users', ['id' => $user->id, 'status' => 'active']);
    }

    // AC6: Cannot Deactivate Self
    public function test_admin_cannot_deactivate_self(): void
    {
        $response = $this->actingAs($this->admin)->delete(route('admin.users.destroy', $this->admin));

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('error', 'Không thể vô hiệu hóa tài khoản của chính mình');
        $this->assertDatabaseHas('users', ['id' => $this->admin->id, 'status' => 'active']);
    }

    // AC4 + Login: Deactivated user cannot login
    public function test_deactivated_user_cannot_login(): void
    {
        $user = User::factory()->create([
            'status' => 'inactive',
            'email' => 'inactive@test.com',
            'password' => bcrypt('password'),
            'role_id' => $this->admin->role_id,
        ]);

        $response = $this->post(route('admin.login.submit'), [
            'email' => 'inactive@test.com',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    // AC7: Non-Admin Access Denied
    public function test_non_admin_cannot_access_user_list(): void
    {
        $response = $this->actingAs($this->manager)->get(route('admin.users.index'));
        $response->assertStatus(403);
    }

    public function test_non_admin_cannot_create_user(): void
    {
        $response = $this->actingAs($this->manager)->get(route('admin.users.create'));
        $response->assertStatus(403);
    }

    public function test_non_admin_cannot_store_user(): void
    {
        $response = $this->actingAs($this->manager)->post(route('admin.users.store'), [
            'username' => 'test',
            'email' => 'test@test.com',
            'full_name' => 'Test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role_id' => 1,
        ]);
        $response->assertStatus(403);
    }

    // Validation Tests
    public function test_create_user_requires_username(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.users.store'), [
            'email' => 'test@test.com',
            'full_name' => 'Test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role_id' => 1,
        ]);

        $response->assertSessionHasErrors('username');
    }

    public function test_create_user_requires_unique_username(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.users.store'), [
            'username' => $this->admin->username, // Already exists
            'email' => 'unique@test.com',
            'full_name' => 'Test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role_id' => 1,
        ]);

        $response->assertSessionHasErrors('username');
    }

    public function test_create_user_requires_unique_email(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.users.store'), [
            'username' => 'uniqueuser',
            'email' => $this->admin->email, // Already exists
            'full_name' => 'Test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role_id' => 1,
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_create_user_requires_password_confirmation(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.users.store'), [
            'username' => 'testuser',
            'email' => 'test@test.com',
            'full_name' => 'Test',
            'password' => 'password123',
            'password_confirmation' => 'different',
            'role_id' => 1,
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_create_user_requires_min_password_length(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.users.store'), [
            'username' => 'testuser',
            'email' => 'test@test.com',
            'full_name' => 'Test',
            'password' => 'short',
            'password_confirmation' => 'short',
            'role_id' => 1,
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_update_user_allows_same_username_for_same_user(): void
    {
        $user = User::factory()->create(['role_id' => $this->admin->role_id]);

        $response = $this->actingAs($this->admin)->put(route('admin.users.update', $user), [
            'username' => $user->username, // Same username
            'email' => $user->email,
            'full_name' => 'Updated Name',
            'role_id' => $user->role_id,
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');
    }
}
