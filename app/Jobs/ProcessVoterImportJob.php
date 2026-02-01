<?php

namespace App\Jobs;

use App\Services\VoterImportService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessVoterImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** Job timeout in seconds (1 hour for large files). */
    public $timeout = 3600;

    /** Number of times to attempt the job. */
    public $tries = 1;

    public function __construct(
        protected string $storedPath,
        protected array $commonFields,
        protected ?int $userId = null
    ) {}

    public function handle(): void
    {
        $fullPath = Storage::path($this->storedPath);

        if (!file_exists($fullPath)) {
            Log::error('Voter import: file not found: ' . $this->storedPath);
            $this->storeResult(['success' => 0, 'duplicates' => 0, 'errors' => ['File not found.']]);
            return;
        }

        try {
            $results = VoterImportService::runImport($fullPath, $this->commonFields);
            $this->storeResult($results);
        } catch (\Throwable $e) {
            Log::error('Voter import failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            $this->storeResult([
                'success' => 0,
                'duplicates' => 0,
                'errors' => ['Import failed: ' . $e->getMessage()],
            ]);
        } finally {
            Storage::delete($this->storedPath);
        }
    }

    protected function storeResult(array $results): void
    {
        $key = 'voter_import_result_' . ($this->userId ?? 0);
        Cache::put($key, [
            'success' => $results['success'] ?? 0,
            'duplicates' => $results['duplicates'] ?? 0,
            'error_count' => count($results['errors'] ?? []),
            'errors' => array_slice($results['errors'] ?? [], 0, 50),
            'completed_at' => now()->toIso8601String(),
        ], now()->addHours(2));
    }
}
