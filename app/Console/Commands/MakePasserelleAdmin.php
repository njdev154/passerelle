<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MakePasserelleAdmin extends Command
{
    protected $signature = 'passerelle:make-admin {email : Adresse e-mail du compte à promouvoir}';

    protected $description = 'Attribue le rôle administrateur à un compte Passerelle existant';

    public function handle(): int
    {
        $user = User::query()->where('email', $this->argument('email'))->first();

        if (! $user) {
            $this->error('Aucun compte ne correspond à cette adresse e-mail.');

            return self::FAILURE;
        }

        $user->update(['role' => 'admin', 'status' => 'active']);
        $this->info($user->email.' est maintenant administrateur.');

        return self::SUCCESS;
    }
}
