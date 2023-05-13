<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class CreateUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new users in database for app usage';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        User::truncate();

        $data = [
            [
                "name" => "Zbygor Gor",
                "email" => "zbygor.gor@gmail.com",
                "password" => "password123"
            ],
            [
                "name" => "Uršula Šula",
                "email" => "ursula.sula@gmail.com",
                "password" => "password123"
            ],
            [
                "name" => "Maximilián Lián",
                "email" => "max.lian@gmail.com",
                "password" => "password123"
            ],
        ];

        foreach ($data as $item){
            $user = new User();

            $user->fill($item);

            $user->save();

            $user->generate2faSecret();
        }
        $this->info('Generation completed');
        return 1;
    }
}
