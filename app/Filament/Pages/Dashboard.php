<?php

namespace App\Filament\Pages;

use App\Filament\Resources\PendudukResource\Widgets\DateOfBirthChart;
use App\Filament\Resources\PendudukResource\Widgets\GenderDistributionChart;
use App\Filament\Resources\PendudukResource\Widgets\AgeDistributionChart;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Actions\FilterAction;
use Filament\Pages\Dashboard\Concerns\HasFiltersAction;

class Dashboard extends BaseDashboard
{
    public function getColumns(): int | string | array
    {
        return 2;
    }

    protected int | string | array $columnSpan = 'full';
}
