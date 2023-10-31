<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class StatusUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'status:user {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Give user status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = User::where('email', $this->argument('email'))->firstOrFail();

        if(!$user->status) {
            $this->error("User {$this->argument('email')} already has status 1");
            return;
        }

        $user->status = 0;
        $user->save();

        $this->info("User {$this->argument('email')} got status 1");
    }
}
