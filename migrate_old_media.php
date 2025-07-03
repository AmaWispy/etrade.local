<?php

require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use Spatie\Image\Image;
use Spatie\Image\Manipulations;

// Настройка подключения к базе данных
$capsule = new Capsule;
$capsule->addConnection([
    'driver'    => 'mysql', // или другой драйвер
    'host'      => 'localhost',
    'database'  => 'etrade_db',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

// Пути к папкам
$oldMediaPath = __DIR__ . '/storage/app/public/old-media/';
$newMediaPath = __DIR__ . '/storage/app/public/media/';

// Создаем директории для нового медиа
$directories = [
    $newMediaPath,
    $newMediaPath . 'conversions/',
    $newMediaPath . 'conversions/thumb/',
    $newMediaPath . 'conversions/medium/',
    $newMediaPath . 'conversions/main/'
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
        echo "Created directory: $dir\n";
    }
}

// Функция для генерации UUID
function generateUuid() {
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

// Функция для создания разных размеров изображения
function createImageConversions($sourcePath, $fileName, $mediaId) {
    global $newMediaPath;
    
    $conversions = [];
    $sizes = [
        'thumb' => ['width' => 100, 'height' => 100],
        'medium' => ['width' => 400, 'height' => 400], 
        'main' => ['width' => 992, 'height' => 992]
    ];
    
    foreach ($sizes as $sizeName => $dimensions) {
        try {
            $outputPath = $newMediaPath . 'conversions/' . $sizeName . '/' . $mediaId . '/' . $fileName;
            $outputDir = dirname($outputPath);
            
            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0755, true);
            }
            
            // Создаем изображение с нужными размерами
            $image = Image::load($sourcePath);
            
            if ($sizeName === 'thumb' || $sizeName === 'medium') {
                // Для thumb и medium используем фиксированные размеры с обрезкой
                $image->width($dimensions['width'])
                      ->height($dimensions['height'])
                      ->sharpen(10);
            } else {
                // Для main используем fit чтобы не превышать размеры
                $image->fit(Manipulations::FIT_MAX, $dimensions['width'], $dimensions['height']);
            }
            
            $image->save($outputPath);
                
            $conversions[$sizeName] = true;
            echo "  Created {$sizeName}: {$outputPath}\n";
            
        } catch (Exception $e) {
            echo "  Error creating {$sizeName}: " . $e->getMessage() . "\n";
            $conversions[$sizeName] = false;
        }
    }
    
    return $conversions;
}

// Настройки тестирования
$testMode = isset($argv[1]) && $argv[1] === '--test-one';

// Основная логика миграции
try {
    echo "Starting migration of old_media...\n\n";
    
    if ($testMode) {
        echo "TEST MODE: Processing only the first record\n\n";
    }
    
    // Получаем записи из old_media
    $query = Capsule::table('old_media');
    if ($testMode) {
        $query->limit(1);
    }
    $oldMediaRecords = $query->get();
    
    echo "Found " . $oldMediaRecords->count() . " records in old_media table\n\n";
    
    $processedCount = 0;
    $errorCount = 0;
    $skippedCount = 0;
    
    foreach ($oldMediaRecords as $oldRecord) {
        echo "Processing: {$oldRecord->name} (SKU: {$oldRecord->sku})\n";
        
        // Проверяем существование файла
        $sourceFile = $oldMediaPath . $oldRecord->name;
        if (!file_exists($sourceFile)) {
            echo "  File not found: {$sourceFile}\n";
            $skippedCount++;
            continue;
        }
        
        // Ищем продукт по SKU
        $product = Capsule::table('products')->where('sku', $oldRecord->sku)->first();
        if (!$product) {
            echo "  Product not found for SKU: {$oldRecord->sku}\n";
            $skippedCount++;
            continue;
        }
        
        try {
            // Получаем информацию о файле
            $fileInfo = pathinfo($oldRecord->name);
            $fileName = $fileInfo['basename'];
            $mimeType = mime_content_type($sourceFile);
            $fileSize = filesize($sourceFile);
            
            // Создаем UUID для записи
            $uuid = generateUuid();
            
            // Копируем оригинальный файл в новую папку
            $newFileName = $uuid . '.' . $fileInfo['extension'];
            $newFilePath = $newMediaPath . $newFileName;
            
            if (!copy($sourceFile, $newFilePath)) {
                throw new Exception("Failed to copy file to new location");
            }
            
            // Создаем записи в таблице media
            $mediaId = Capsule::table('media')->insertGetId([
                'model_type' => 'App\\Models\\Shop\\Product',
                'model_id' => $product->id,
                'uuid' => $uuid,
                'collection_name' => 'product-images',
                'name' => $fileInfo['filename'],
                'file_name' => $newFileName,
                'mime_type' => $mimeType,
                'disk' => 'public',
                'conversions_disk' => 'public',
                'size' => $fileSize,
                'manipulations' => '[]',
                'custom_properties' => '[]',
                'generated_conversions' => json_encode([
                    'thumb' => true,
                    'medium' => true, 
                    'main' => true
                ]),
                'responsive_images' => '[]',
                'order_column' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            echo "  Created media record with ID: {$mediaId}\n";
            
            // Создаем конверсии изображений
            $conversions = createImageConversions($sourceFile, $newFileName, $mediaId);
            
            // Обновляем запись с информацией о конверсиях
            Capsule::table('media')
                ->where('id', $mediaId)
                ->update([
                    'generated_conversions' => json_encode($conversions),
                    'updated_at' => now()
                ]);
            
            $processedCount++;
            echo "  ✓ Successfully processed {$oldRecord->name}\n\n";
            
        } catch (Exception $e) {
            echo "  ✗ Error processing {$oldRecord->name}: " . $e->getMessage() . "\n\n";
            $errorCount++;
        }
    }
    
    echo "\n" . str_repeat("=", 50) . "\n";
    echo "Migration completed!\n";
    echo "Processed: {$processedCount}\n";
    echo "Skipped: {$skippedCount}\n";
    echo "Errors: {$errorCount}\n";
    echo str_repeat("=", 50) . "\n";
    
} catch (Exception $e) {
    echo "Fatal error: " . $e->getMessage() . "\n";
    exit(1);
}

function now() {
    return date('Y-m-d H:i:s');
} 