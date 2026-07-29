<?php

namespace Tests\Feature;

use App\Models\Servicio;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServicioTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_services(): void
    {
        $this->get(route('servicios.index'))->assertRedirect(route('login'));
        $this->post(route('servicios.store'), [])->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_create_a_service_owned_by_them(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $response = $this->actingAs($user)->post(route('servicios.store'), [
            ...$this->validData(),
            'user_id' => $otherUser->id,
        ]);

        $response->assertRedirect(route('servicios.index'));
        $this->assertDatabaseHas('servicios', [
            'nombre' => 'Cambio de aceite',
            'user_id' => $user->id,
        ]);
        $this->assertDatabaseMissing('servicios', [
            'nombre' => 'Cambio de aceite',
            'user_id' => $otherUser->id,
        ]);
    }

    public function test_service_validation_rejects_invalid_values(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->from(route('servicios.create'))
            ->post(route('servicios.store'), [
                'nombre' => '',
                'precio' => -1,
                'duracion_estimada' => 0,
                'estado' => 'Estado inventado',
            ]);

        $response->assertRedirect(route('servicios.create'));
        $response->assertSessionHasErrors(['nombre', 'precio', 'duracion_estimada', 'estado']);
        $this->assertDatabaseCount('servicios', 0);
    }

    public function test_owner_can_update_their_service(): void
    {
        $user = User::factory()->create();
        $servicio = $this->createService($user);

        $response = $this->actingAs($user)->put(route('servicios.update', $servicio), [
            ...$this->validData(),
            'nombre' => 'Alineación y balanceo',
            'estado' => 'En espera',
        ]);

        $response->assertRedirect(route('servicios.index'));
        $this->assertDatabaseHas('servicios', [
            'id' => $servicio->id,
            'nombre' => 'Alineación y balanceo',
            'estado' => 'En espera',
        ]);
    }

    public function test_another_user_cannot_update_or_delete_the_service(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $servicio = $this->createService($owner);

        $this->actingAs($otherUser)
            ->put(route('servicios.update', $servicio), $this->validData())
            ->assertForbidden();

        $this->actingAs($otherUser)
            ->delete(route('servicios.destroy', $servicio))
            ->assertForbidden();

        $this->assertDatabaseHas('servicios', ['id' => $servicio->id]);
    }

    public function test_owner_can_delete_their_service(): void
    {
        $user = User::factory()->create();
        $servicio = $this->createService($user);

        $this->actingAs($user)
            ->delete(route('servicios.destroy', $servicio))
            ->assertRedirect(route('servicios.index'));

        $this->assertDatabaseMissing('servicios', ['id' => $servicio->id]);
    }

    private function createService(User $user): Servicio
    {
        return $user->servicios()->create($this->validData());
    }

    private function validData(): array
    {
        return [
            'nombre' => 'Cambio de aceite',
            'descripcion' => 'Cambio de aceite y filtro.',
            'precio' => 250,
            'duracion_estimada' => 45,
            'estado' => 'Activo',
        ];
    }
}
