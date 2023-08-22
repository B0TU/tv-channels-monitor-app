<?php

namespace App\Filament\Resources\ChannelResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use App\Filament\Resources\ChannelResource;
use Filament\Resources\Pages\ManageRecords;

class ManageChannels extends ManageRecords
{
    protected static string $resource = ChannelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
