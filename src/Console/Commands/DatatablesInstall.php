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
        $this->info('Installing Laravel Datatables Package By Amprest');

        // Create a progress bar of n actions
        $bar = $this->output->createProgressBar(2);

        //  Start the progress bar
        $bar->start();

        //  Publish all public asset files
        $this->call('vendor:publish', [
            '--tag' => 'datatables-assets',
            '--force' => true,
        ]);

        //  Advance the progress bar
        $bar->advance();

        //  Publish all publishable config files
        $this->call('vendor:publish', [
            '--tag' => 'datatables-config',
            '--force' => false,
        ]);

        //  Advance the progress bar
        $bar->advance();

        //  Finish the progress bar
        $bar->finish();

        //  Completed 
        $this->info('Success! Completed Installation. Enjoy.');
    }
}
