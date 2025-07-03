<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Shop\Product;
use Exception;

class MigrateOldMediaImproved extends Command
{
    protected $signature = 'migrate:old-media-improved 
                            {--dry-run : Run without making changes} 
                            {--batch-size=10 : Number of records to process in each batch}
                            {--start-from=0 : Start from specific record number}
                            {--memory-limit=512M : Set PHP memory limit}
                            {--timeout=300 : Max seconds per record}';

    protected $description = 'Improved migration for old_media images with resume capability';

    private $processedCount = 0;
    private $errorCount = 0;
    private $skippedCount = 0;
    private $startTime;

    public function handle()
    {
        $this->startTime = microtime(true);
        
        $dryRun = $this->option('dry-run');
        $batchSize = (int) $this->option('batch-size');
        $startFrom = (int) $this->option('start-from');
        $memoryLimit = $this->option('memory-limit');
        $timeout = (int) $this->option('timeout');

        // Устанавливаем лимиты
        ini_set('memory_limit', $memoryLimit);
        set_time_limit($timeout * $batchSize + 60);

        $this->info("🚀 Starting improved migration...");
        $this->info("Memory limit: {$memoryLimit}");
        $this->info("Batch size: {$batchSize}");
        $this->info("Starting from record: {$startFrom}");
        
        if ($dryRun) {
            $this->warn('DRY RUN MODE - No changes will be made');
        }

        // Получаем общее количество записей
        $totalRecords = DB::table('old_media')->count();
        $this->info("Total records: {$totalRecords}");

        if ($startFrom >= $totalRecords) {
            $this->error("Start position ({$startFrom}) is beyond total records ({$totalRecords})");
            return 1;
        }

        $remainingRecords = $totalRecords - $startFrom;
        $this->info("Records to process: {$remainingRecords}");

        $bar = $this->output->createProgressBar($remainingRecords);
        $bar->start();

        // Обрабатываем записи батчами
        $currentOffset = $startFrom;
        
        try {
            while ($currentOffset < $totalRecords) {
                $this->info("\n🔄 Processing batch starting from record: " . ($currentOffset + 1));
                
                // Получаем батч записей
                $records = DB::table('old_media')
                    ->skip($currentOffset)
                    ->take($batchSize)
                    ->get();

                if ($records->isEmpty()) {
                    break;
                }

                foreach ($records as $index => $record) {
                    $recordNumber = $currentOffset + $index + 1;
                    
                    try {
                        $result = $this->processMediaRecord($record, $dryRun, $recordNumber);
                        
                        switch ($result['status']) {
                            case 'processed':
                                $this->processedCount++;
                                $this->line(" ✅ #{$recordNumber}: {$record->name}");
                                break;
                            case 'skipped':
                                $this->skippedCount++;
                                $this->line(" ⏭️  #{$recordNumber}: {$result['message']}");
                                break;
                            case 'error':
                                $this->errorCount++;
                                $this->line(" ❌ #{$recordNumber}: {$result['message']}");
                                Log::error("Migration error #{$recordNumber}: {$result['message']}");
                                break;
                        }
                        
                    } catch (Exception $e) {
                        $this->errorCount++;
                        $this->line(" 💥 #{$recordNumber}: Fatal error - {$e->getMessage()}");
                        Log::error("Fatal migration error #{$recordNumber}: " . $e->getMessage());
                    }
                    
                    $bar->advance();
                    
                    // Освобождаем память каждые 10 записей
                    if (($recordNumber % 10) === 0) {
                        gc_collect_cycles();
                    }
                }

                $currentOffset += $batchSize;
                
                // Пауза между батчами
                if (!$dryRun) {
                    sleep(1);
                }
                
                // Выводим промежуточную статистику
                $this->showProgress($currentOffset, $totalRecords);
            }
            
        } catch (Exception $e) {
            $this->error("\n💥 Critical error: " . $e->getMessage());
            $this->info("\n🔄 To resume from where you left off:");
            $this->info("php artisan migrate:old-media-improved --start-from={$currentOffset}");
            return 1;
        }

        $bar->finish();
        
        $this->showFinalResults($totalRecords);
        return 0;
    }

    private function processMediaRecord($oldRecord, $dryRun, $recordNumber)
    {
        $oldMediaPath = storage_path('app/public/old-media/' . $oldRecord->name);
        
        if (!file_exists($oldMediaPath)) {
            return [
                'status' => 'skipped',
                'message' => "File not found: {$oldRecord->name}"
            ];
        }

        // Проверяем размер файла
        $fileSize = filesize($oldMediaPath);
        $fileSizeMB = round($fileSize / 1024 / 1024, 2);
        
        if ($fileSizeMB > 50) {
            return [
                'status' => 'skipped',
                'message' => "File too large: {$oldRecord->name} ({$fileSizeMB}MB)"
            ];
        }

        // Проверяем формат
        $supportedFormats = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $fileExtension = strtolower(pathinfo($oldRecord->name, PATHINFO_EXTENSION));
        
        if (!in_array($fileExtension, $supportedFormats)) {
            return [
                'status' => 'skipped',
                'message' => "Unsupported format: {$oldRecord->name} (.{$fileExtension})"
            ];
        }

        $product = Product::where('sku', $oldRecord->sku)->first();
        
        if (!$product) {
            return [
                'status' => 'skipped',
                'message' => "Product not found for SKU: {$oldRecord->sku}"
            ];
        }

        // Проверяем дубликаты
        $existingMedia = $product->getMedia('product-images')
            ->where('name', pathinfo($oldRecord->name, PATHINFO_FILENAME))
            ->first();
            
        if ($existingMedia) {
            return [
                'status' => 'skipped', 
                'message' => "Media already exists: {$oldRecord->name}"
            ];
        }

        if ($dryRun) {
            return [
                'status' => 'processed',
                'message' => "Would process: {$oldRecord->name}"
            ];
        }

        try {
            $media = $product
                ->addMedia($oldMediaPath)
                ->usingName(pathinfo($oldRecord->name, PATHINFO_FILENAME))
                ->usingFileName($oldRecord->name)
                ->toMediaCollection('product-images');

            return [
                'status' => 'processed',
                'message' => "Processed: {$oldRecord->name} (Media ID: {$media->id})"
            ];
            
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => "Processing error: {$oldRecord->name} - {$e->getMessage()}"
            ];
        }
    }

    private function showProgress($current, $total)
    {
        $percent = round(($current / $total) * 100, 1);
        $elapsed = microtime(true) - $this->startTime;
        $rate = $current / ($elapsed / 60); // записей в минуту
        
        $this->info("\n📊 Progress: {$current}/{$total} ({$percent}%)");
        $this->info("⏱️  Rate: " . round($rate, 1) . " records/min");
        $this->info("✅ Processed: {$this->processedCount}");
        $this->info("⏭️  Skipped: {$this->skippedCount}");  
        $this->info("❌ Errors: {$this->errorCount}");
        
        // Оценка времени завершения
        if ($rate > 0) {
            $remaining = $total - $current;
            $eta = round($remaining / $rate);
            $this->info("🎯 ETA: ~{$eta} minutes");
        }
    }

    private function showFinalResults($total)
    {
        $elapsed = round((microtime(true) - $this->startTime) / 60, 1);
        
        $this->newLine(2);
        $this->info('🎉 Migration completed!');
        $this->table(['Status', 'Count', 'Percentage'], [
            ['Processed', $this->processedCount, round(($this->processedCount / $total) * 100, 1) . '%'],
            ['Skipped', $this->skippedCount, round(($this->skippedCount / $total) * 100, 1) . '%'],
            ['Errors', $this->errorCount, round(($this->errorCount / $total) * 100, 1) . '%'],
            ['Total', $total, '100%']
        ]);
        $this->info("⏱️  Total time: {$elapsed} minutes");
    }
}