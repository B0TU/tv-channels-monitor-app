<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\Error;
use App\Models\Channel;
use App\Models\ScanLog;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '10s';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $firstRecord = ScanLog::orderBy('created_at')->first(); // Get the first record
        $lastRecord = ScanLog::orderBy('created_at', 'desc')->first(); // Get the last record

        $startTime = strtotime($firstRecord->created_at); // Convert to UNIX timestamp
        $endTime = strtotime($lastRecord->created_at); // Convert to UNIX timestamp

        $timeDifferenceSeconds = $endTime - $startTime; // Calculate the time difference in seconds

        $timeDifferenceMinutes = floor($timeDifferenceSeconds / 60); // Calculate minutes
        $remainingSeconds = $timeDifferenceSeconds % 60; // Calculate remaining seconds

        $timeDifferenceFormatted = sprintf('%02d:%02d', $timeDifferenceMinutes, $remainingSeconds);

        echo $timeDifferenceFormatted; // Output: MM:SS

        return [
            Stat::make('Total Channels', Channel::count()),
            Stat::make('Predefined Errors', Error::count()),
            Stat::make('Last Scan Duration', $timeDifferenceFormatted),
        ];
    }
}
