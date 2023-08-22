A simple laravel application to capture screenshot of added tv channels / streaming urls and check for any predfined errors.

1. Laravel (As the development framework)
2. Filamentphp (For dashboard development)
3. protonemedia/laravel-ffmpeg (To capture screenshots)
4. thiagoalessio/tesseract-ocr-for-php (To recognize error messages / texts on the captured screenshots)
5. php intervention/image (To apply filters to screenshots to make it easier for Tesseract OCR to read texts on the images more accurately)

Other features will be added later 
Spoiler Alert!
(Opt-ins: Notifications on errors and scans)

Installation:
Make sure you have installed FFMpeg and Tesseract OCR into you server / system.

1. git clone https://github.com/B0TU/tv-channels-monitor-app
2. Composer install
