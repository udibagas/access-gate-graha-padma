<?php

namespace App\Jobs;

use App\Events\CameraErrorEvent;
use App\Events\MemberTapEvent;
use App\Models\AccessLog;
use App\Models\Camera;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class TakeSnapshot implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $accessLog;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(AccessLog $accessLog)
    {
        $this->accessLog = $accessLog;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = new Client(['timeout' => 1]);
        $cameras = Camera::whereIn('id', $this->accessLog->cardReader->camera_ids)->get();

        foreach ($cameras as $camera) {
            try {
                $response = $client->request('GET', $camera->url, [
                    'auth' => [
                        $camera->user,
                        $camera->pass,
                        'digest'
                    ]
                ]);

                $fileName = $camera->name . date('-YmdHis') . '.jpeg';
                $path = 'snapshots/' . date('Y/m/d/H/') . $fileName;
                Storage::put($path, $response->getBody());
            } catch (\Exception $e) {
                event(new CameraErrorEvent($camera));
                continue;
            }

            $this->accessLog->snapshots()->create([
                'camera_id' => $camera->id,
                'path' => $path,
                'filename' => $fileName,
            ]);
        }

        $this->accessLog->refresh();
        event(new MemberTapEvent($this->accessLog));
    }
}
