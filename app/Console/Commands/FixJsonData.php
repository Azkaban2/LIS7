<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\OrderRequest;

class FixJsonData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:json-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix JSON data in the programs and orders fields of OrderRequest table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting JSON data fix...');

        // Fetch all records with invalid JSON
        $orderRequests = OrderRequest::all();

        foreach ($orderRequests as $orderRequest) {
            $fixed = false;

            // Fix programs field
            if (!is_array($orderRequest->programs)) {
                $orderRequest->programs = json_decode($orderRequest->programs, true);
                if (!is_array($orderRequest->programs)) {
                    $orderRequest->programs = [];
                }
                $fixed = true;
            }

            // Fix orders field
            if (!is_array($orderRequest->order)) {
                $orderRequest->order = json_decode($orderRequest->order, true);
                if (!is_array($orderRequest->order)) {
                    $orderRequest->order = [];
                }
                $fixed = true;
            }

            if ($fixed) {
                $orderRequest->save();
                $this->info("Fixed record ID: {$orderRequest->id}");
            }
        }

        $this->info('JSON data fix completed.');
    }
}
