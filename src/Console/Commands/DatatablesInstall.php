<?php

namespace Amprest\LaravelDatatables\Console\Commands;

use Illuminate\Console\Command;

class DatatablesInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'datatables:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Installs the datatables component package';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //  Publish all public files
        $this->call('vendor:publish', [
            '--tag' => 'public',
            '--force' => true,
        ]);
    }
}
