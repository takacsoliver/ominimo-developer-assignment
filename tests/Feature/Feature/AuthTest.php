<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace Tests\Feature\Feature;

use OminimoBlog\Models\User;
use OminimoBlog\Enums\Role;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{

    public function testGuestCanViewLoginPage(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertInertia(fn($page) => 
            $page->component('Auth/Login')
        );
    }

    public function testGuestCanViewRegisterPage(): void
    {
        $response = $this->get(route('register'));

        $response->assertStatus(200);
        $response->assertInertia(fn($page) => 
            $page->component('Auth/Register')
        );
    }

    public function testGuestCanViewForgotPasswordPage(): void
    {
        $response = $this->get(route('password.request'));

        $response->assertStatus(200);
        $response->assertInertia(fn($page) => 
            $page->component('Auth/ForgotPassword')
        );
    }

    public function testUserCanLoginWithValidCredentials(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
        ]);
        $user->assignRole(\OminimoBlog\Enums\Role::USER->value);

        $response = $this->post(route('login'), [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('posts.index'));
        $this->assertAuthenticatedAs($user);
    }

    public function testUserCannotLoginWithInvalidCredentials(): void
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post(route('login'), [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function testUserCanRegisterWithValidData(): void
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post(route('register'), $userData);

        $response->assertRedirect(route('posts.index'));
        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);
    }

    public function testUserCannotRegisterWithExistingEmail(): void
    {
        User::factory()->create(['email' => 'existing@example.com']);

        $userData = [
            'name' => 'John Doe',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post(route('register'), $userData);

        $response->assertSessionHasErrors(['email']);
    }

    public function testUserCannotRegisterWithMismatchedPasswords(): void
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'differentpassword',
        ];

        $response = $this->post(route('register'), $userData);

        $response->assertSessionHasErrors(['password']);
    }

    public function testUserCanLogout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('logout'));

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    public function testGuestCannotLogout(): void
    {
        $response = $this->post(route('logout'));

        $response->assertRedirect(route('login'));
    }

    public function testUserCanViewProfile(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('profile.edit'));

        $response->assertStatus(200);
        $response->assertInertia(fn($page) => 
            $page->component('Profile/Edit')
                ->has('user')
                ->where('user.id', $user->id)
        );
    }

    public function testGuestCannotViewProfile(): void
    {
        $response = $this->get(route('profile.edit'));

        $response->assertRedirect(route('login'));
    }

    public function testUserCanUpdateProfile(): void
    {
        $user = User::factory()->create([
            'name' => 'Original Name',
            'email' => 'original@example.com',
        ]);

        $updateData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ];

        $response = $this->actingAs($user)->put(route('profile.update'), $updateData);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);
    }

    public function testUserCanUpdatePassword(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('oldpassword'),
        ]);

        $passwordData = [
            'current_password' => 'oldpassword',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ];

        $response = $this->actingAs($user)->put(route('password.update'), $passwordData);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('success');
        $user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $user->password));
    }

    public function testUserCannotUpdatePasswordWithWrongCurrentPassword(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('oldpassword'),
        ]);

        $passwordData = [
            'current_password' => 'wrongpassword',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ];

        $response = $this->actingAs($user)->put(route('password.update'), $passwordData);

        $response->assertSessionHasErrors(['current_password']);
    }

    public function testUserCanDeleteAccount(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $deleteData = [
            'password' => 'password123',
        ];

        $response = $this->actingAs($user)->delete(route('profile.destroy'), $deleteData);

        $response->assertRedirect('/');
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function testUserCannotDeleteAccountWithWrongPassword(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $deleteData = [
            'password' => 'wrongpassword',
        ];

        $response = $this->actingAs($user)->delete(route('profile.destroy'), $deleteData);

        $response->assertSessionHasErrors(['password']);
        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    public function testRegisteredUserGetsUserRole(): void
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $this->post(route('register'), $userData);

        $user = User::where('email', 'john@example.com')->first();
        $this->assertTrue($user->hasRole(Role::USER->value));
    }

    public function testLoginValidation(): void
    {
        $response = $this->post(route('login'), []);

        $response->assertSessionHasErrors(['email', 'password']);
    }

    public function testRegisterValidation(): void
    {
        $response = $this->post(route('register'), []);

        $response->assertSessionHasErrors(['name', 'email', 'password']);
    }

    public function testProfileUpdateValidation(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put(route('profile.update'), []);

        $response->assertSessionHasErrors(['name', 'email']);
    }

    public function testPasswordResetRequest(): void
    {
        $user = User::factory()->create(['email' => 'test@example.com']);

        $response = $this->post(route('password.email'), [
            'email' => 'test@example.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');
    }

    public function testPasswordResetRequestWithInvalidEmail(): void
    {
        $response = $this->post(route('password.email'), [
            'email' => 'nonexistent@example.com',
        ]);

        $response->assertSessionHasErrors(['email']);
    }
}