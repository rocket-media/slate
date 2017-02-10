<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\FormRequest;


class GetRequestKey extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rocket-forms:request-key';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates a key for a rocket forms request';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(FormRequest $formRequest)
    {
        $this->line($formRequest->getKey());
    }
}