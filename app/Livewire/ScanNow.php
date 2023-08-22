<?php

namespace App\Livewire;

use App\Models\Error;
use App\Models\Channel;
use Livewire\Component;
use App\Jobs\ScanNowJob;
use App\Jobs\SendNotificationJob;
use Illuminate\Support\Facades\File;
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
        /*$tesseractPath = "C:/Program Files/Tesseract-OCR/tesseract.exe";
        $channels = \App\Models\Channel::all();
        $tempDirectory = storage_path('app/public/');
        File::cleanDirectory($tempDirectory);

        foreach ($channels as $index => $channel) {
            $screenshotFilename = "{$channel->name}.jpg";
            $img = $tempDirectory . $screenshotFilename;

            FFMpeg::open($channel->streaming_url)
                //->getFrameFromSeconds(10)
                ->export()
                ->toDisk('public')
                ->save($screenshotFilename);

            try {
                
                $recognizedText = (new TesseractOCR($img))
                ->executable($tesseractPath)
                ->run();
                
            } catch (\Exception $e) {
                $this->errors[] = "{$channel->name} - No output";
                continue;
            }

            $predefinedErrors = Error::pluck('error_description')->toArray();

            $errorMatch = false;
            if ($recognizedText) {
                foreach ($predefinedErrors as $error) {
                    if (stripos($recognizedText, $error) !== false) {
                        $errorMatch = true;
                        $this->errors[] = $channel->name . ' - ' . $recognizedText;
                        break;
                    }
                }
            } else {
                $this->errors[] = $channel->name . ' - No issues, all good!';
            }

            if (!$errorMatch) {
                $this->errors[] = $channel->name . ' - No issues, all good!';
            }

        }
        dd($this->errors);*/
        
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
