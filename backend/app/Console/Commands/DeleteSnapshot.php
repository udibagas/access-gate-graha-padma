<?php

namespace App\Console\Commands;

use App\Models\Snapshot;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DeleteSnapshot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'snapshot:delete {date}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete snapshot based on date';

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
        $snapshots = Snapshot::where('created_at', '<=', $this->argument('date'))->get();

        foreach ($snapshots as $snapshot) {
            $this->line("Deleteting {$snapshot->path} ...");
            if (Storage::exists($snapshot->path)) {
                Storage::delete($snapshot->path);
                $this->info('File deleted');
            } else {
                $this->error('File not found');
            }

            $snapshot->delete();
            $this->info('Record deleted');
        }
    }
}
