<?php

namespace Binomedev\Contact\Commands;

use Illuminate\Console\Command;

class ContactCommand extends Command
{
    public $signature = 'contact';

    public $description = 'My command';

    public function handle()
    {
        $this->comment('All done');
    }
}
