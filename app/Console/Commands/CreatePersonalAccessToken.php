<?php

namespace App\Console\Commands;

use Exception;
use Ignite\Interfaces\Users\UserService;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Sanctum\Contracts\HasApiTokens;
use Symfony\Component\Console\Attribute\AsCommand;
use Throwable;

#[AsCommand(name: 'token:personal')]
class CreatePersonalAccessToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'token:personal {email} {tokenName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new personal access token';

    /**
     * Execute the console command.
     *
     * @param \Ignite\Interfaces\Users\UserService $service
     *
     * @return int
     */
    public function handle(UserService $service)
    {
        $email = $this->argument('email');

        try {
            $user = $service->findBy('email', $email);
        } catch (ModelNotFoundException $e) {
            $this->error("User with email {$email} does not exist.");

            return Command::FAILURE;
        }

        try {
            if (! $user instanceof HasApiTokens) {
                throw new Exception('User model must use the "HasApiTokens" trait.');
            }

            $token = $user->createToken($this->argument('tokenName'));
        } catch (Throwable $e) {
            $this->error($e->getMessage());

            return Command::FAILURE;
        }

        $this->info('Make sure to copy your personal access token now. You won’t be able to see it again!');
        $this->line('Token: ' . $token->plainTextToken);

        return Command::SUCCESS;
    }
}
