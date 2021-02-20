<?php

namespace App\Console\Commands;

use Ifsnop\Mysqldump\Mysqldump;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class Backup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup database';

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
     * @return int
     */
    public function handle()
    {
        try {
            $dump = new Mysqldump(
                'mysql:host=' . env('DB_HOST') . ';dbname=' . env('DB_DATABASE'),
                env('DB_USERNAME'),
                env('DB_PASSWORD'),
                ['add-drop-table' => true]
            );

            $dump->start(Storage::path('backup/access_gate_backup_' . date('Y-m-d-H-i-s') . '.sql'));
            $this->info('Backup berhasil!');
        } catch (\Exception $e) {
            $this->error('backup Gagal! ' . $e->getMessage());
        }
    }
}
