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

$oldMediaPath = __DIR__ . '/storage/app/public/old-media/';

echo "=== ДИАГНОСТИКА ПРОБЛЕМНОЙ ЗАПИСИ ===\n\n";

try {
    // Получаем записи с 98 по 102 (где произошло зависание)
    $records = Capsule::table('old_media')->skip(97)->take(5)->get();
    
    foreach ($records as $index => $record) {
        $recordNumber = 98 + $index;
        echo "📋 Запись #{$recordNumber}:\n";
        echo "   ID: {$record->id}\n";
        echo "   SKU: {$record->sku}\n";
        echo "   Name: {$record->name}\n";
        
        $sourceFile = $oldMediaPath . $record->name;
        echo "   File: {$sourceFile}\n";
        
        // Проверяем файл
        if (!file_exists($sourceFile)) {
            echo "   ❌ Файл не найден\n\n";
            continue;
        }
        
        $fileSize = filesize($sourceFile);
        $fileSizeMB = round($fileSize / 1024 / 1024, 2);
        
        echo "   📏 Размер: {$fileSizeMB} MB\n";
        
        // Проверяем тип файла
        $mimeType = mime_content_type($sourceFile);
        echo "   🎭 MIME: {$mimeType}\n";
        
        // Проверяем расширение
        $extension = strtolower(pathinfo($record->name, PATHINFO_EXTENSION));
        echo "   📄 Расширение: {$extension}\n";
        
        // Предупреждения
        if ($fileSizeMB > 10) {
            echo "   ⚠️  БОЛЬШОЙ ФАЙЛ! Может вызвать проблемы с памятью\n";
        }
        
        if (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            echo "   ⚠️  НЕСТАНДАРТНОЕ РАСШИРЕНИЕ! Может вызвать проблемы\n";
        }
        
        // Проверяем продукт
        $product = Capsule::table('products')->where('sku', $record->sku)->first();
        if (!$product) {
            echo "   ❌ Продукт не найден\n";
        } else {
            echo "   ✅ Продукт найден (ID: {$product->id})\n";
        }
        
        echo "\n" . str_repeat("-", 50) . "\n\n";
    }
    
    echo "🎯 РЕКОМЕНДАЦИИ:\n";
    echo "1. Если есть большие файлы (>10MB) - увеличьте memory_limit в PHP\n";
    echo "2. Если есть нестандартные форматы - пропустите их или конвертируйте\n";
    echo "3. Проверьте права доступа к папкам storage/app/public/media/\n";
    echo "4. Запустите миграцию с меньшим batch-size: --batch-size=10\n\n";
    
} catch (Exception $e) {
    echo "❌ Ошибка: " . $e->getMessage() . "\n";
} 