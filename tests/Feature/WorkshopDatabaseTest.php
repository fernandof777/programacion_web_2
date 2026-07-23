<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class WorkshopDatabaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_normalized_workshop_tables_exist(): void
    {
        foreach ([
            'roles', 'permissions', 'clients', 'mechanics', 'vehicles',
            'appointments', 'services', 'work_orders', 'work_order_services',
            'parts', 'inventories', 'inventory_movements', 'invoices', 'payments',
        ] as $table) {
            $this->assertTrue(Schema::hasTable($table), "Missing table: {$table}");
        }
    }

    public function test_vehicle_requires_an_existing_client_and_model(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        DB::table('vehicles')->insert([
            'client_id' => 999,
            'vehicle_model_id' => 999,
            'license_plate' => 'INVALID',
            'current_mileage' => 0,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_license_plate_is_unique(): void
    {
        $clientId = DB::table('clients')->insertGetId([
            'full_name' => 'Cliente de prueba',
            'phone' => '70000000',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $makeId = DB::table('vehicle_makes')->insertGetId([
            'name' => 'Toyota', 'created_at' => now(), 'updated_at' => now(),
        ]);
        $modelId = DB::table('vehicle_models')->insertGetId([
            'vehicle_make_id' => $makeId,
            'name' => 'Corolla',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $vehicle = [
            'client_id' => $clientId,
            'vehicle_model_id' => $modelId,
            'license_plate' => 'ABC-123',
            'current_mileage' => 0,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        DB::table('vehicles')->insert($vehicle);
        $this->expectException(\Illuminate\Database\QueryException::class);
        DB::table('vehicles')->insert($vehicle);
    }
}
