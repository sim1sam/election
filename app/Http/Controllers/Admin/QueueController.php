<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QueueController extends Controller
{
    /**
     * Show queue run page (admin menu: Process queue).
     */
    public function index()
    {
        $pendingCount = 0;
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('jobs')) {
                $pendingCount = DB::table('jobs')->count();
            }
        } catch (\Throwable $e) {
            Log::warning('Queue index: could not count jobs', ['error' => $e->getMessage()]);
        }
        return view('admin.queue.index', compact('pendingCount'));
    }

    /**
     * Run queue worker (processes pending jobs).
     * Uses Artisan::call so it works on all servers; may run in same request for a short time.
     */
    public function process(Request $request)
    {
        try {
            // Run queue until empty (max 10 minutes in this request)
            Artisan::call('queue:work', [
                '--stop-when-empty' => true,
                '--tries' => 3,
                '--max-time' => 600,
            ]);

            $output = trim(Artisan::output());
            return redirect()->route('admin.queue.index')
                ->with('success', 'Queue processed. ' . ($output ?: 'Pending jobs have been run. Check Bulk Upload for import results.'));
        } catch (\Throwable $e) {
            Log::error('Queue process failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->route('admin.queue.index')
                ->with('error', 'Queue run failed: ' . $e->getMessage());
        }
    }
}
