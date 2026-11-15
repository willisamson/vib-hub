<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(ContentSeeder::class);

        // Nur lokal einen Test-Admin anlegen. Auf dem Server legt
        // setup-vibhub.sh den echten Admin an (make:filament-user).
        if (app()->environment('local') && ! User::query()->where('email', 'admin@vib-hub.test')->exists()) {
            User::factory()->create([
                'name' => 'Admin',
                'email' => 'admin@vib-hub.test',
                'password' => 'password',
            ]);
        }
    }
}
