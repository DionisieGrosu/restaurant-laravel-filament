<?php

namespace App\Jobs;

use App\Models\Food;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class DeleteUnusedImages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Collection $food)
    {
        $this->food = $food;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $files = Storage::disk('public')->allFiles('foods');
        foreach ($files as $file) {
            $check_if_exists = Food::where('img', $file)->first();
            if (!$check_if_exists) {
                Storage::disk('public')->delete($file);
            }
        }
    }
}
