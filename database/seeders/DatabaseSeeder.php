<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $now = now();

        foreach (['Administrador', 'Recepcionista', 'Mecánico', 'Cliente'] as $role) {
            DB::table('roles')->updateOrInsert(
                ['name' => $role],
                ['description' => "Rol {$role}", 'created_at' => $now, 'updated_at' => $now],
            );
        }

        foreach (['Efectivo', 'Tarjeta', 'Transferencia', 'QR'] as $method) {
            DB::table('payment_methods')->updateOrInsert(
                ['name' => $method],
                ['is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            );
        }

        foreach (['Mantenimiento preventivo', 'Mecánica general', 'Electricidad', 'Diagnóstico'] as $category) {
            DB::table('service_categories')->updateOrInsert(
                ['name' => $category],
                ['created_at' => $now, 'updated_at' => $now],
            );
        }

        DB::table('branches')->updateOrInsert(
            ['name' => 'Sucursal Principal'],
            [
                'address' => 'Por configurar',
                'city' => 'Santa Cruz de la Sierra',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        );

        $admin = User::factory()->create([
            'name' => 'Administrador',
            'email' => 'admin@autowork.local',
        ]);

        DB::table('role_user')->insert([
            'role_id' => DB::table('roles')->where('name', 'Administrador')->value('id'),
            'user_id' => $admin->id,
        ]);
    }
}
