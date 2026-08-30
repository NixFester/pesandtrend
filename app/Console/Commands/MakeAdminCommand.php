<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class MakeAdminCommand extends Command
{
    protected $signature = 'pesantrends:make-admin {email}';

    protected $description = 'Assign admin role to a user by email';

    public function handle(): int
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->error("User with email [{$email}] not found.");

            return self::FAILURE;
        }

        $role = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $user->assignRole($role);
        $user->update(['role' => 'admin']);

        $this->info("User [{$user->name}] is now an admin.");

        return self::SUCCESS;
    }
}
