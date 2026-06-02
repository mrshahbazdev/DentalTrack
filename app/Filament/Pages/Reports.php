<?php

namespace App\Filament\Pages;

use App\Exports\OrdersExport;
use App\Exports\ScanEventsExport;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class Reports extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-arrow-down';

    public static function getNavigationLabel(): string
    {
        return __('app.nav.reports_export');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('app.nav.analytics');
    }

    protected static ?int $navigationSort = 13;

    protected static string $view = 'filament.pages.reports';

    public string $dateFrom = '';

    public string $dateTo = '';

    public string $selectedCompany = '';

    public string $exportType = 'orders';

    public function mount(): void
    {
        $this->dateFrom = Carbon::now()->subDays(30)->format('Y-m-d');
        $this->dateTo = Carbon::now()->format('Y-m-d');
    }

    public function exportExcel(): BinaryFileResponse
    {
        $companyId = $this->selectedCompany !== '' ? (int) $this->selectedCompany : null;
        $filename = "{$this->exportType}-{$this->dateFrom}-to-{$this->dateTo}.xlsx";

        if ($this->exportType === 'scan_events') {
            return Excel::download(
                new ScanEventsExport($this->dateFrom, $this->dateTo, $companyId),
                $filename
            );
        }

        return Excel::download(
            new OrdersExport($this->dateFrom, $this->dateTo, $companyId),
            $filename
        );
    }

    public function exportCsv(): BinaryFileResponse
    {
        $companyId = $this->selectedCompany !== '' ? (int) $this->selectedCompany : null;
        $filename = "{$this->exportType}-{$this->dateFrom}-to-{$this->dateTo}.csv";

        if ($this->exportType === 'scan_events') {
            return Excel::download(
                new ScanEventsExport($this->dateFrom, $this->dateTo, $companyId),
                $filename,
                \Maatwebsite\Excel\Excel::CSV
            );
        }

        return Excel::download(
            new OrdersExport($this->dateFrom, $this->dateTo, $companyId),
            $filename,
            \Maatwebsite\Excel\Excel::CSV
        );
    }
}
