<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:make-admin {email : بريد المستخدم المراد ترقيته}')]
#[Description('ترقية مستخدم إلى مدير عبر بريده الإلكتروني')]
class MakeAdmin extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->error("لا يوجد مستخدم بالبريد: {$email}");
            return self::FAILURE;
        }

        $user->update(['role' => User::ROLE_ADMIN]);
        $this->info("تمت ترقية «{$user->name}» ({$email}) إلى مدير ✓");

        return self::SUCCESS;
    }
}
