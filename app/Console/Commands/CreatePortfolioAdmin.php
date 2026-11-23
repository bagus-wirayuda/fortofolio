<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

#[Signature('portfolio:admin {email} {--generate : Generate a random password}')]
#[Description('Create a portfolio administrator without public registration')]
class CreatePortfolioAdmin extends Command
{
    public function handle(): int
    {
        $email = $this->argument('email');
        if (! filter_var($email, FILTER_VALIDATE_EMAIL) || User::query()->where('email', $email)->exists()) {
            $this->error('Use a valid email address that is not already registered.');

            return self::FAILURE;
        }
        $password = $this->option('generate') ? Str::password(20) : $this->secret('Password (at least 12 characters, uppercase, lowercase and number)');
        if (! is_string($password) || strlen($password) < 12 || ! preg_match('/[A-Z]/', $password) || ! preg_match('/[a-z]/', $password) || ! preg_match('/[0-9]/', $password)) {
            $this->error('Password does not meet the requirements.');

            return self::FAILURE;
        }
        $user = new User(['name' => 'Portfolio Admin', 'email' => $email, 'password' => $password]);
        $user->is_admin = true;
        $user->save();
        $this->info('Administrator created: '.$email);
        if ($this->option('generate')) {
            $this->line('Temporary password: '.$password);
        }

        return self::SUCCESS;
    }
}
