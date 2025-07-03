<?php

require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

// Настройка подключения к базе данных
$capsule = new Capsule;
$capsule->addConnection([
    'driver'    => 'mysql',
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

echo "=== ТЕСТ МИГРАЦИИ ОДНОЙ ЗАПИСИ ===\n\n";

try {
    // Получаем только первую запись
    $oldRecord = Capsule::table('old_media')->first();
    
    if (!$oldRecord) {
        echo "❌ Нет записей в таблице old_media\n";
        exit(1);
    }
    
    echo "📋 Тестируем запись:\n";
    echo "   ID: {$oldRecord->id}\n";
    echo "   SKU: {$oldRecord->sku}\n";
    echo "   Name: {$oldRecord->name}\n";
    echo "   Product ID: {$oldRecord->product_id}\n";
    echo "   Media ID: {$oldRecord->media_id}\n\n";
    
    // Проверяем файл
    $sourceFile = $oldMediaPath . $oldRecord->name;
    echo "🔍 Проверяем файл: {$sourceFile}\n";
    
    if (!file_exists($sourceFile)) {
        echo "❌ Файл не найден\n";
        exit(1);
    }
    
    $fileSize = filesize($sourceFile);
    $mimeType = mime_content_type($sourceFile);
    
    echo "✅ Файл найден\n";
    echo "   Размер: " . number_format($fileSize / 1024, 2) . " KB\n";
    echo "   MIME: {$mimeType}\n\n";
    
    // Проверяем продукт
    echo "🔍 Ищем продукт с SKU: {$oldRecord->sku}\n";
    $product = Capsule::table('products')->where('sku', $oldRecord->sku)->first();
    
    if (!$product) {
        echo "❌ Продукт не найден\n";
        exit(1);
    }
    
    echo "✅ Продукт найден\n";
    echo "   ID: {$product->id}\n";
    echo "   Name: {$product->name}\n\n";
    
    // Проверяем существующие медиа
    echo "🔍 Проверяем существующие медиа для продукта...\n";
    $existingMedia = Capsule::table('media')
        ->where('model_type', 'App\\Models\\Shop\\Product')
        ->where('model_id', $product->id)
        ->where('collection_name', 'product-images')
        ->count();
        
    echo "📊 Найдено медиа: {$existingMedia}\n\n";
    
    // Показываем что будет создано
    echo "📝 Что будет создано:\n";
    echo "   ✓ Запись в таблице media\n";
    echo "   ✓ Оригинальный файл: storage/app/public/media/[uuid].ext\n";
    echo "   ✓ Конверсия thumb: 100x100px\n";
    echo "   ✓ Конверсия medium: 400x400px\n";
    echo "   ✓ Конверсия main: 992x992px (fit max)\n\n";
    
    echo "🎯 ТЕСТ ЗАВЕРШЕН УСПЕШНО!\n";
    echo "Теперь можете запустить полную миграцию:\n";
    echo "php artisan migrate:old-media --test-one --dry-run\n";
    echo "php artisan migrate:old-media --test-one\n\n";
    
} catch (Exception $e) {
    echo "❌ Ошибка: " . $e->getMessage() . "\n";
    exit(1);
} 