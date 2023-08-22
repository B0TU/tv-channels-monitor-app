<?php

namespace App\Filament\Widgets;

use App\Models\ScanLog;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentScanResults extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(ScanLog::query())
            ->columns([
                TextColumn::make('channel_name')->sortable(),
                // TextColumn::make('message')->description('this is the error from the system')->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'OK' => 'success',
                        'Error' => 'danger',
                    })->description(fn (ScanLog $record): string => $record->message),
                TextColumn::make('created_at')->since()->sortable(),
            ])
            ->poll('10s')
            ->filters([
                // ...
            ])
            ->actions([
                // ...
            ])
            ->bulkActions([
                // ...
            ]);
    }

}