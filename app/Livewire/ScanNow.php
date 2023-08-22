<?php

namespace App\Livewire;

use App\Models\Error;
use App\Models\Channel;
use Livewire\Component;
use App\Jobs\ScanNowJob;
use App\Jobs\SendNotificationJob;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Filament\Notifications\Notification;
use thiagoalessio\TesseractOCR\TesseractOCR;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class ScanNow extends Component
{
    public $errors = [];

    public function render()
    {
        return view('livewire.scan-now');
    }

    public function scanNow()
    {
        ScanNowJob::dispatch();

        Notification::make()
        ->title("Channels Scan Started")
        ->info()
        ->sendToDatabase(auth()->user());

        Notification::make()
        ->title("Channels Scan Started")
        ->info()
        ->send();


    }
}
