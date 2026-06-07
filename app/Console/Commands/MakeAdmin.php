<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

#[Signature('app:make-admin {email? : بريد المدير} {--password= : كلمة المرور} {--name= : الاسم} {--bootstrap : لا يفعل شيئاً إن كان هناك مدير بالفعل}')]
#[Description('إنشاء أو ترقية حساب مدير (يقرأ ADMIN_EMAIL/ADMIN_PASSWORD إن لم تُمرّر)')]
class MakeAdmin extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // وضع التأسيس التلقائي: إن كان هناك مدير بالفعل لا نلمس شيئاً
        // (حتى لا تُستبدل تعديلات المستخدم لبريده/كلمة سرّه عند كل نشر).
        if ($this->option('bootstrap') && User::where('role', User::ROLE_ADMIN)->exists()) {
            $this->info('يوجد مدير بالفعل — تخطّي التزويد التلقائي.');
            return self::SUCCESS;
        }

        $email = $this->argument('email') ?: env('ADMIN_EMAIL') ?: 'mjcoz.bk@gmail.com';
        $password = $this->option('password') ?: env('ADMIN_PASSWORD') ?: '21436578Mm';
        $name = $this->option('name') ?: 'مدير';

        if (blank($email)) {
            $this->error('حدّد البريد عبر الوسيط أو متغيّر ADMIN_EMAIL.');
            return self::FAILURE;
        }

        $user = User::where('email', $email)->first();

        if ($user) {
            $updates = ['role' => User::ROLE_ADMIN];
            if (filled($password)) {
                $updates['password'] = Hash::make($password);
            }
            $user->update($updates);
            $this->info("تمت ترقية «{$user->name}» ({$email}) إلى مدير ✓");

            return self::SUCCESS;
        }

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make(filled($password) ? $password : Str::random(16)),
            'role' => User::ROLE_ADMIN,
        ]);
        $this->info("تم إنشاء حساب مدير جديد ({$email}) ✓");

        return self::SUCCESS;
    }
}
