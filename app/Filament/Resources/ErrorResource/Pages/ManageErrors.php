<?php

namespace App\Filament\Resources\ErrorResource\Pages;

use App\Filament\Resources\ErrorResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageErrors extends ManageRecords
{
    protected static string $resource = ErrorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
