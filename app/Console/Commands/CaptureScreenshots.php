<?php

namespace App\Console\Commands;

use App\Models\Error;
use App\Models\Channel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use app\Services\CaptureScreenshotService;
use thiagoalessio\TesseractOCR\TesseractOCR;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class CaptureScreenshots extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Capture screenshots for all channels';

    protected $errors = [];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Processing...');

        $tesseractPath = "C:/Program Files/Tesseract-OCR/tesseract.exe";
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

        //$this->info('Screenshots captured successfully.');
        $this->info(implode("\n", $this->errors));
    }
}
