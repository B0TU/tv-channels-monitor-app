<?php

namespace App\Jobs;

use App\Models\Error;
use App\Models\Channel;
use App\Models\ScanLog;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\File;
use FFMpeg\Filters\Video\VideoFilters;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use thiagoalessio\TesseractOCR\TesseractOCR;
use Illuminate\Contracts\Queue\ShouldBeUnique;

use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use Intervention\Image\ImageManagerStatic as Image;

class ScanNowJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $errors = [];
    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    // public function handle(): void
    // {

    //     $tesseractPath = "C:/Program Files/Tesseract-OCR/tesseract.exe";
    //     $screenshotsDirectory = storage_path('app/public/screenshots/');
    //     $channels = Channel::all();
    //     File::cleanDirectory($screenshotsDirectory);

    //     $scanLogs = [];

    //     try {
    //         foreach ($channels as $index => $channel) {
    //             $screenshotFilename = "{$channel->name}.jpg";
    //             $img = $screenshotsDirectory . $screenshotFilename;

    //             FFMpeg::open($channel->streaming_url)
    //                 //->getFrameFromSeconds(10)
    //                 ->export()
    //                 ->toDisk('screenshots')
    //                 ->save($screenshotFilename);

    //             $image = Image::make($img)->invert();
    //             $image->brightness(10);
    //             $image->contrast(30);
    //             $image->greyscale();

    //             // $width = $image->width();
    //             // $height = $image->height();

    //             // for ($x = 0; $x < $width; $x++) {
    //             //     for ($y = 0; $y < $height; $y++) {
    //             //         $pixel = $image->pickColor($x, $y, 'array');

    //             //         // Detect if the pixel color is darker than a threshold (adjust threshold as needed)
    //             //         $isDark = array_sum($pixel) < 400; // Adjust threshold value

    //             //         // If pixel is considered dark, set it to black
    //             //         if ($isDark || $pixel[0] < 100) { // Also consider very low red value as text
    //             //             $image->pixel([0, 0, 0], $x, $y);
    //             //         }
    //             //     }
    //             // }

    //             $image->save();

    //             try {

    //                 $recognizedText = (new TesseractOCR($img))
    //                     ->executable($tesseractPath)
    //                     ->run();
    //             } catch (\Exception $e) {
    //                 $this->errors[] = "{$channel->name} - No output";
    //                 $scanLogs[] = [
    //                     'channel_name' => $channel->name,
    //                     'status' => 'OK',
    //                     'message' => 'No errors output was generated, seems all good!',
    //                     'created_at' => now(),
    //                 ];
    //                 continue;
    //             }

    //             $predefinedErrors = Error::pluck('error_description')->toArray();

    //             $errorMatch = false;
    //             if ($recognizedText) {
    //                 foreach ($predefinedErrors as $error) {
    //                     if (stripos($recognizedText, $error) !== false) {
    //                         $errorMatch = true;
    //                         $this->errors[] = $channel->name . ' - ' . $recognizedText;
    //                         $scanLogs[] = [
    //                             'channel_name' => $channel->name,
    //                             'status' => 'Error',
    //                             'message' => $recognizedText,
    //                             'created_at' => now(),
    //                         ];
    //                         break;
    //                     }
    //                 }
    //             } else {

    //                 $this->errors[] = $channel->name . ' - No issues, all good!';
    //                 $scanLogs[] = [
    //                     'channel_name' => $channel->name,
    //                     'status' => 'OK',
    //                     'message' => 'No issues, all good!',
    //                     'created_at' => now(),
    //                 ];
    //             }

    //             if (!$errorMatch) {
    //                 $this->errors[] = $channel->name . ' - No issues, all good!';
    //                 $scanLogs[] = [
    //                     'channel_name' => $channel->name,
    //                     'status' => 'OK',
    //                     'message' => 'No issues, all good!',
    //                     'created_at' => now(),
    //                 ];
    //             }
    //         }
    //     } finally {

    //         ScanLog::truncate();
    //         ScanLog::insert($scanLogs);
    //     }
    // }

    public function handle(): void
    {
        Artisan::call('cs');
    }
}
