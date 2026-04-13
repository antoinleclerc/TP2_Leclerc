<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;



class AuthentificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_success()
    {
        $response = $this->postJson('/api/signup', [
            'login' => 'lola',
            'email' => 'lola@gmail.com',
            'password' => '123456789a',
            'password_confirmation' => '123456789a',
        ]);
        $response->assertStatus(201);
    }

    public function test_register_validation_error()
    {
        $response = $this->postJson('/api/signup', [
            'login' => '',
            'email' => 'mauvais',
            'password' => 'short',
            'password_confirmation' => 'short',
        ]);
        $response->assertStatus(422);
    }

    public function test_login_success()
    {
        $createResponse = $this->postJson('/api/signup', [
                'login' => 'toto',
                'email' => 'toto@example.com',
                'password' => 'password12',
                'password_confirmation' => 'password12',
        ]);
        $response = $this->postJson('/api/signin', [
            'login' => 'toto',
            'password' => 'password12',
            'password_confirmation' => 'password12',
        ]);
        $createResponse->assertStatus(201);
        $response->assertStatus(200);
    }

    public function test_login_invalid_info()
    {
        $this->postJson('/api/signup', [
            'login' => 'lola',
            'email' => 'lola@gmail.com',
            'password' => '123456789a',
            'password_confirmation' => '123456789a',
        ]);

        $response = $this->postJson('/api/signin', [
            'login' => 'lola',
            'password' => 'wrongpassword',
        ]);
        $response->assertStatus(401);
            
    }

    public function test_register_login_already_taken()
    {
        User::factory()->create(['login' => 'toto']);
        $response = $this->postJson('/api/signup', [
            'login' => 'toto',
            'email' => 'toto@example.com',
            'password' => 'password12',
            'password_confirmation' => 'password12',
        ]);
        $response->assertStatus(422);
    }

    /* Le withHeader vient de chatgpt qui ma donner cette source https://laravel.com/docs/master/http-client#headers

    how can i make a php test with an token, dont give me the answer

Parfait, je vais te guider sans te donner la solution directe 👀

📚 Source à consulter

👉 https://laravel.com/docs/master/http-client#headers

🧠 Ce que tu dois comprendre

Dans un test Laravel, tu simules une requête HTTP.
Un token d’authentification ne va pas dans l’URL ni dans le body, mais dans les headers.

🔑 Indices importants
La doc montre comment :
ajouter des headers à une requête (withHeaders)
ou utiliser un helper lié aux tokens (withToken)
Un token est généralement envoyé comme :
Authorization: Bearer TON_TOKEN
    */
    public function test_logout_success()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/signout');
        $response->assertStatus(204);
    }

    public function test_logout_unauthenticated()
    {
        $response = $this->postJson('/api/signout');
        $response->assertStatus(401);
    }

    public function test_register_throttling()
    {
        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/signup', [
                'login' => 'toto' . $i,
                'email' => 'toto' . $i . '@example.com',
                'password' => 'password12',
                'password_confirmation' => 'password12',
            ]);
        }
        $response = $this->postJson('/api/signup', [
            'login' => 'toto6',
            'email' => 'toto6@example.com',
            'password' => 'password12',
            'password_confirmation' => 'password12',
        ]);
        $response->assertStatus(429);
    }
}

