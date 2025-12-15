<?php

namespace Tests\Feature\Customer;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    // ========================================
    // AC1: View Profile Page Tests
    // ========================================

    public function test_profile_page_displays_customer_information(): void
    {
        $customer = Customer::factory()->create([
            'full_name' => 'Nguyễn Văn A',
            'email' => 'test@example.com',
            'phone' => '0912345678',
            'points' => 500,
        ]);

        $response = $this->actingAs($customer, 'customer')
            ->get('/profile');

        $response->assertStatus(200);
        $response->assertSee('Nguyễn Văn A');
        $response->assertSee('test@example.com');
        $response->assertSee('0912345678');
        $response->assertSee('500'); // points
    }

    public function test_profile_page_requires_authentication(): void
    {
        $response = $this->get('/profile');

        $response->assertRedirect('/login');
    }

    public function test_profile_update_requires_authentication(): void
    {
        $response = $this->put('/profile', [
            'full_name' => 'Test User',
            'phone' => '0912345678',
        ]);

        $response->assertRedirect('/login');
    }

    public function test_password_change_requires_authentication(): void
    {
        $response = $this->put('/profile/password', [
            'current_password' => 'oldpassword',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect('/login');
    }

    // ========================================
    // AC2: Update Profile Information Tests
    // ========================================

    public function test_customer_can_update_profile_information(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->actingAs($customer, 'customer')
            ->put('/profile', [
                'full_name' => 'Trần Văn B',
                'phone' => '0987654321',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Cập nhật thông tin thành công');

        $customer->refresh();
        $this->assertEquals('Trần Văn B', $customer->full_name);
        $this->assertEquals('0987654321', $customer->phone);
    }


    public function test_profile_update_validates_full_name_required(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->actingAs($customer, 'customer')
            ->put('/profile', [
                'full_name' => '',
                'phone' => '0912345678',
            ]);

        $response->assertSessionHasErrors(['full_name']);
    }

    public function test_profile_update_validates_phone_format(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->actingAs($customer, 'customer')
            ->put('/profile', [
                'full_name' => 'Test User',
                'phone' => 'invalid-phone',
            ]);

        $response->assertSessionHasErrors(['phone']);
    }

    public function test_profile_update_allows_empty_phone(): void
    {
        $customer = Customer::factory()->create(['phone' => '0912345678']);

        $response = $this->actingAs($customer, 'customer')
            ->put('/profile', [
                'full_name' => 'Test User',
                'phone' => '',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $customer->refresh();
        $this->assertNull($customer->phone);
    }

    // ========================================
    // AC3: Change Password Tests
    // ========================================

    public function test_customer_can_change_password_with_correct_current_password(): void
    {
        $customer = Customer::factory()->create([
            'password' => 'oldpassword123',
        ]);

        $response = $this->actingAs($customer, 'customer')
            ->put('/profile/password', [
                'current_password' => 'oldpassword123',
                'password' => 'newpassword456',
                'password_confirmation' => 'newpassword456',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('password_success', 'Đổi mật khẩu thành công');

        $customer->refresh();
        $this->assertTrue(Hash::check('newpassword456', $customer->password));
    }

    public function test_session_remains_active_after_password_change(): void
    {
        $customer = Customer::factory()->create([
            'password' => 'oldpassword123',
        ]);

        $this->actingAs($customer, 'customer')
            ->put('/profile/password', [
                'current_password' => 'oldpassword123',
                'password' => 'newpassword456',
                'password_confirmation' => 'newpassword456',
            ]);

        // Verify still authenticated
        $this->assertAuthenticated('customer');
    }

    // ========================================
    // AC4: Incorrect Current Password Tests
    // ========================================

    public function test_password_change_fails_with_incorrect_current_password(): void
    {
        $customer = Customer::factory()->create([
            'password' => 'correctpassword',
        ]);

        $response = $this->actingAs($customer, 'customer')
            ->put('/profile/password', [
                'current_password' => 'wrongpassword',
                'password' => 'newpassword456',
                'password_confirmation' => 'newpassword456',
            ]);

        $response->assertSessionHasErrors(['current_password']);

        $customer->refresh();
        $this->assertTrue(Hash::check('correctpassword', $customer->password));
    }

    public function test_password_change_validates_minimum_length(): void
    {
        $customer = Customer::factory()->create([
            'password' => 'oldpassword123',
        ]);

        $response = $this->actingAs($customer, 'customer')
            ->put('/profile/password', [
                'current_password' => 'oldpassword123',
                'password' => 'short',
                'password_confirmation' => 'short',
            ]);

        $response->assertSessionHasErrors(['password']);
    }

    public function test_password_change_validates_confirmation(): void
    {
        $customer = Customer::factory()->create([
            'password' => 'oldpassword123',
        ]);

        $response = $this->actingAs($customer, 'customer')
            ->put('/profile/password', [
                'current_password' => 'oldpassword123',
                'password' => 'newpassword456',
                'password_confirmation' => 'differentpassword',
            ]);

        $response->assertSessionHasErrors(['password']);
    }

    // ========================================
    // AC5: View Loyalty Points Tests
    // ========================================

    public function test_loyalty_points_display_with_vnd_equivalent(): void
    {
        $customer = Customer::factory()->create([
            'points' => 250,
        ]);

        $response = $this->actingAs($customer, 'customer')
            ->get('/profile');

        $response->assertStatus(200);
        $response->assertSee('250'); // points
        $response->assertSee('250,000'); // VND equivalent (250 * 1000) - uses comma as thousands separator
    }

    public function test_profile_shows_points_earning_explanation(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->actingAs($customer, 'customer')
            ->get('/profile');

        $response->assertStatus(200);
        $response->assertSee('Tích 1 điểm cho mỗi 100.000đ mua hàng');
    }
}
