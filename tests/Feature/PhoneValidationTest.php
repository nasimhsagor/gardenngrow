<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhoneValidationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function contact_form_accepts_valid_bangladeshi_phone_numbers(): void
    {
        $validNumbers = ['01712345678', '+8801812345678', '8801912345678', '01512345678', '01612345678', '01312345678', '01412345678'];

        foreach ($validNumbers as $number) {
            $response = $this->post(route('page.contact.submit'), [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'phone' => $number,
                'subject' => 'Test Subject',
                'message' => 'This is a test message of at least a reasonable length.'
            ]);

            $response->assertSessionHasNoErrors();
        }
    }

    /** @test */
    public function contact_form_rejects_invalid_phone_numbers(): void
    {
        $invalidNumbers = [
            '01212345678',  // Invalid operator code 2
            '0171234567',   // Too short
            '017123456789', // Too long
            'abcdefghijk',  // Non-numeric
            '+8801712345',  // Prefix with invalid length
        ];

        foreach ($invalidNumbers as $number) {
            $response = $this->post(route('page.contact.submit'), [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'phone' => $number,
                'subject' => 'Test Subject',
                'message' => 'This is a test message of at least a reasonable length.'
            ]);

            $response->assertSessionHasErrors(['phone']);
        }
    }

    /** @test */
    public function register_validation_accepts_valid_phone_format(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '01712345678',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!'
        ]);
        $response->assertSessionHasNoErrors();
    }

    /** @test */
    public function register_validation_rejects_invalid_phone_format(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'John Doe',
            'email' => 'john2@example.com',
            'phone' => '01212345678', // Invalid operator
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!'
        ]);
        $response->assertSessionHasErrors(['phone']);
    }
}
