<?php

namespace App\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Services\SaleImportService;

class ImportSaleJob implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    public function __construct(
        protected string $file,
        protected int|null $contaCanalDeVendaId,
        protected int $enterpriseId,
        protected int $acessoId
    ){}

    public function handle(SaleImportService $saleImportService): void
    {
        try
        {
            $filePath       = storage_path('app' . DIRECTORY_SEPARATOR . $this->file);
            $fileExtension  = pathinfo($filePath, PATHINFO_EXTENSION);
    
            $saleImportService->processFile(
                $filePath,
                $this->contaCanalDeVendaId,
                $this->enterpriseId,
                $this->acessoId,
                $fileExtension
            );
        }
        catch (Exception $e)
        {
            Log::error('Error processing the Excel file: ', ['error' => $e->getMessage()]);
        }
        Storage::delete($this->file);
    }
}
