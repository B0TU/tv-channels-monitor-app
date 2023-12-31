<?php

namespace App\Console\Commands;

use App\Models\Error;
use App\Models\Channel;
use App\Models\ScanLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use thiagoalessio\TesseractOCR\TesseractOCR;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use Intervention\Image\ImageManagerStatic as Image;

class CaptureScreenshots extends Command
{
    protected $signature = 'cs';
    protected $description = 'Capture screenshots of all channels and scan for any predeined error messages';

    protected $errors = [];

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $this->info('Scanning...');

        $tesseractPath = "C:/Program Files/Tesseract-OCR/tesseract.exe";
        $screenshotsDirectory = storage_path('app/public/screenshots/');
        $channels = Channel::all();
        File::cleanDirectory($screenshotsDirectory);

        $scanLogs = [];

        try {
            foreach ($channels as $index => $channel) {
                $screenshotFilename = "{$channel->name}.jpg";
                $img = $screenshotsDirectory . $screenshotFilename;

                FFMpeg::openUrl($channel->streaming_url)
                    ->getFrameFromSeconds(3)
                    ->export()
                    ->toDisk('screenshots')
                    ->save($screenshotFilename);

                $image = Image::make($img)->invert();
                $image->brightness(10);
                $image->contrast(30);
                $image->greyscale();

                $image->save();

                try {

                    $recognizedText = (new TesseractOCR($img))
                        ->executable($tesseractPath)
                        ->run();
                } catch (\Exception $e) {
                    $this->errors[] = "{$channel->name} - No output";
                    $scanLogs[] = [
                        'channel_name' => $channel->name,
                        'status' => 'OK',
                        'message' => 'No errors output was generated, seems all good!',
                        'created_at' => now(),
                    ];
                    continue;
                }

                $predefinedErrors = Error::pluck('error_description')->toArray();

                $errorMatch = false;
                if ($recognizedText) {
                    foreach ($predefinedErrors as $error) {
                        if (stripos($recognizedText, $error) !== false) {
                            $errorMatch = true;
                            $this->errors[] = $channel->name . ' - ' . $recognizedText;
                            $scanLogs[] = [
                                'channel_name' => $channel->name,
                                'status' => 'Error',
                                'message' => $recognizedText,
                                'created_at' => now(),
                            ];
                            break;
                        }
                    }
                } else {

                    $this->errors[] = $channel->name . ' - No issues, all good!';
                    $scanLogs[] = [
                        'channel_name' => $channel->name,
                        'status' => 'OK',
                        'message' => 'No issues, all good!',
                        'created_at' => now(),
                    ];
                }

                if (!$errorMatch) {
                    $this->errors[] = $channel->name . ' - No issues, all good!';
                    $scanLogs[] = [
                        'channel_name' => $channel->name,
                        'status' => 'OK',
                        'message' => 'No issues, all good!',
                        'created_at' => now(),
                    ];
                }
            }
        } finally {

            ScanLog::truncate();
            ScanLog::insert($scanLogs);

            $this->info('Scan Completed');
        }
    }
}
