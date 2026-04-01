<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $faker = fake();
        $now = now();
        $nowTs = $now->timestamp;

        // Custom tables: tipe, barang, user, stock (if present)
        if (Schema::hasTable('tipe')) {
            $tipes = [];
            for ($i = 0; $i < 20; $i++) {
                $tipes[] = [
                    'tipe' => 'Type ' . ($i + 1),
                ];
            }
            DB::table('tipe')->insert($tipes);
        }

        if (Schema::hasTable('barang')) {
            $tipeNames = Schema::hasTable('tipe')
                ? DB::table('tipe')->pluck('tipe')->all()
                : ['Type 1', 'Type 2', 'Type 3'];

            $barang = [];
            for ($i = 0; $i < 20; $i++) {
                $barang[] = [
                    'nama_barang' => $faker->words(2, true),
                    'tipe' => $faker->randomElement($tipeNames),
                    'stok' => $faker->numberBetween(0, 50),
                    'harga' => $faker->numberBetween(50000, 5000000),
                ];
            }
            DB::table('barang')->insert($barang);
        }

        if (Schema::hasTable('user')) {
            $usersCustom = [];
            for ($i = 0; $i < 20; $i++) {
                $usersCustom[] = [
                    'username' => $faker->unique()->userName(),
                    'email' => $faker->unique()->safeEmail(),
                    'password' => Hash::make('password'),
                    'role' => $faker->numberBetween(1, 4),
                ];
            }
            DB::table('user')->insert($usersCustom);
        }

        if (Schema::hasTable('stock')) {
            $barangIds = Schema::hasTable('barang')
                ? DB::table('barang')->pluck('id_barang')->all()
                : [1];

            $stockRows = [];
            for ($i = 0; $i < 20; $i++) {
                $jumlah = $faker->numberBetween(1, 20);
                $hargaSatuan = $faker->numberBetween(50000, 5000000);
                $stockRows[] = [
                    'id_barang' => $faker->randomElement($barangIds),
                    'jumlah' => $jumlah,
                    'harga_satuan' => $hargaSatuan,
                    'total_harga' => $jumlah * $hargaSatuan,
                    'tipe' => $faker->randomElement(['in', 'out']),
                ];
            }
            DB::table('stock')->insert($stockRows);
        }

        // Users (20)
        $hasEmailVerifiedAt = Schema::hasColumn('users', 'email_verified_at');
        $users = [];
        for ($i = 0; $i < 20; $i++) {
            $user = [
                'name' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
                'created_at' => $now,
                'updated_at' => $now,
            ];
            if ($hasEmailVerifiedAt) {
                $user['email_verified_at'] = $now;
            }
            $users[] = $user;
        }
        DB::table('users')->insert($users);

        // password_reset_tokens (20)
        $passwordResetTokens = [];
        for ($i = 0; $i < 20; $i++) {
            $passwordResetTokens[] = [
                'email' => $faker->unique()->safeEmail(),
                'token' => Str::random(64),
                'created_at' => $now,
            ];
        }
        DB::table('password_reset_tokens')->insert($passwordResetTokens);

        // sessions (20) - attach to existing user ids
        $userIds = DB::table('users')->pluck('id')->all();
        $sessions = [];
        for ($i = 0; $i < 20; $i++) {
            $sessions[] = [
                'id' => (string) Str::uuid(),
                'user_id' => $faker->boolean(70) ? $faker->randomElement($userIds) : null,
                'ip_address' => $faker->ipv4(),
                'user_agent' => $faker->userAgent(),
                'payload' => base64_encode(Str::random(40)),
                'last_activity' => $nowTs - $faker->numberBetween(0, 3600),
            ];
        }
        DB::table('sessions')->insert($sessions);

        // cache (20)
        $cache = [];
        for ($i = 0; $i < 20; $i++) {
            $cache[] = [
                'key' => 'cache_key_' . Str::random(12) . '_' . $i,
                'value' => base64_encode($faker->sentence(6)),
                'expiration' => $nowTs + $faker->numberBetween(300, 86400),
            ];
        }
        DB::table('cache')->insert($cache);

        // cache_locks (20)
        $cacheLocks = [];
        for ($i = 0; $i < 20; $i++) {
            $cacheLocks[] = [
                'key' => 'lock_key_' . Str::random(12) . '_' . $i,
                'owner' => Str::random(16),
                'expiration' => $nowTs + $faker->numberBetween(60, 3600),
            ];
        }
        DB::table('cache_locks')->insert($cacheLocks);

        // jobs (20)
        $jobs = [];
        for ($i = 0; $i < 20; $i++) {
            $jobs[] = [
                'queue' => 'default',
                'payload' => json_encode([
                    'uuid' => (string) Str::uuid(),
                    'displayName' => 'DummyJob',
                    'data' => ['index' => $i],
                ], JSON_UNESCAPED_SLASHES),
                'attempts' => 0,
                'reserved_at' => null,
                'available_at' => $nowTs,
                'created_at' => $nowTs,
            ];
        }
        DB::table('jobs')->insert($jobs);

        // job_batches (20)
        $jobBatches = [];
        for ($i = 0; $i < 20; $i++) {
            $jobBatches[] = [
                'id' => (string) Str::uuid(),
                'name' => 'Batch ' . ($i + 1),
                'total_jobs' => 10,
                'pending_jobs' => 10,
                'failed_jobs' => 0,
                'failed_job_ids' => '[]',
                'options' => null,
                'cancelled_at' => null,
                'created_at' => $nowTs,
                'finished_at' => null,
            ];
        }
        DB::table('job_batches')->insert($jobBatches);

        // failed_jobs (20)
        $failedJobs = [];
        for ($i = 0; $i < 20; $i++) {
            $failedJobs[] = [
                'uuid' => (string) Str::uuid(),
                'connection' => 'database',
                'queue' => 'default',
                'payload' => json_encode([
                    'uuid' => (string) Str::uuid(),
                    'displayName' => 'DummyJob',
                    'data' => ['index' => $i],
                ], JSON_UNESCAPED_SLASHES),
                'exception' => 'Dummy exception for failed job ' . ($i + 1),
                'failed_at' => $now,
            ];
        }
        DB::table('failed_jobs')->insert($failedJobs);
    }
}
