<?php
/**
 * Created by PhpStorm.
 * User: abdulla
 * Date: 20/11/17
 * Time: 06:34 م
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'egyxuploader:uploader {featureName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new upload feature.';

    /**
     * Create a new command instance.
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
        $featureName = $this->argument('featureName');
    }
}
