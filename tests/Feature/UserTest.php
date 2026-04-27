<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;


    /* trouver comment faire tester avec sanctum dix minutes avant la remise de ça https://laracasts.com/discuss/channels/laravel/laravel-sanctum-testing */
    public function test_user_can_update_password(): void
    {
        $this->seed();
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);
        $request = $this->putJson('/api/user/' . $user->id, [
            'password' => 'NewPassword123',
            'password_confirmation' => 'NewPassword123',
        ]);
        $request->assertStatus(200);
    }

    

}
