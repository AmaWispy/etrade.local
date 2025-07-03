# Миграция изображений из old_media

Этот набор скриптов поможет перенести изображения из таблицы `old_media` в новую систему медиа-библиотеки Laravel с автоматическим созданием трех размеров изображений.

## Структура файлов

1. **`create_old_media_table.sql`** - SQL скрипт для создания таблицы old_media
2. **`migrate_old_media.php`** - Standalone PHP скрипт для миграции
3. **`app/Console/Commands/MigrateOldMedia.php`** - Laravel Artisan команда
4. **`product_media_query.sql`** - SQL запрос для выборки связанных данных

## Подготовка

### 1. Создание таблицы old_media (если не существует)
```sql
-- Выполните SQL скрипт
mysql -u username -p database_name < create_old_media_table.sql
```

### 2. Заполнение таблицы old_media
Заполните таблицу `old_media` данными из ваших источников.

### 3. Проверка структуры папок
Убедитесь что папка `/storage/app/public/old-media/` содержит все необходимые изображения.

## Способы миграции

### Вариант 1: Laravel Artisan команда (Рекомендуется)

```bash
# Регистрируем команду в Laravel
php artisan list | grep migrate:old-media

# 🧪 ТЕСТИРОВАНИЕ НА ОДНОЙ ЗАПИСИ:
# Предварительный тест (показывает что будет сделано)
php test_single_media.php

# Тест одной записи без изменений
php artisan migrate:old-media --test-one --dry-run

# Реальный тест одной записи
php artisan migrate:old-media --test-one

# 🚀 ПОЛНАЯ МИГРАЦИЯ:
# Тестовый прогон всех записей (без изменений)
php artisan migrate:old-media --dry-run

# Реальная миграция всех записей
php artisan migrate:old-media

# Миграция с настройкой размера батча
php artisan migrate:old-media --batch-size=100
```

### Вариант 2: Standalone PHP скрипт

```bash
# Настройте подключение к БД в файле migrate_old_media.php

# 🧪 ТЕСТИРОВАНИЕ:
# Тест только одной записи
php migrate_old_media.php --test-one

# 🚀 ПОЛНАЯ МИГРАЦИЯ:
# Миграция всех записей
php migrate_old_media.php
```

## Что делает скрипт

1. **Читает таблицу old_media** - получает все записи для обработки
2. **Проверяет форматы файлов** - поддерживаются: jpg, jpeg, png, gif, webp, bmp, svg
3. **Находит продукты по SKU** - связывает изображения с продуктами
4. **Проверяет существование файлов** - в папке `/storage/app/public/old-media/`
5. **Создает записи в таблице media** - с правильной привязкой к продуктам
6. **Генерирует конверсии изображений**:
   - **thumb**: 100x100px (с обрезкой и резкостью)
   - **medium**: 400x400px (с обрезкой и резкостью)
   - **main**: 992x992px (fit max, без превышения размеров)

**Важно**: Файлы с неподдерживаемыми форматами будут пропущены, но миграция продолжится.

## Структура создаваемых файлов

```
storage/app/public/
├── media/
│   ├── [uuid].[ext]                    # Оригинальные файлы
│   └── conversions/
│       ├── thumb/[media_id]/[file]     # Миниатюры 100x100
│       ├── medium/[media_id]/[file]    # Средние 400x400
│       └── main/[media_id]/[file]      # Основные 992x992 (fit max)
```

## Возможные проблемы и решения

### 1. Файл не найден
```
File not found: example.jpg
```
**Решение**: Проверьте что файл существует в `/storage/app/public/old-media/`

### 2. Продукт не найден
```
Product not found for SKU: ABC123
```
**Решение**: Убедитесь что продукт с таким SKU существует в таблице `products`

### 3. Ошибки обработки изображений
```
Error creating thumb: ...
```
**Решение**: 
- Проверьте что установлена библиотека GD или ImageMagick
- Убедитесь что есть права на запись в папку media
- Проверьте формат изображения (поддерживаются: jpg, jpeg, png, gif, webp, bmp, svg)

### 4. Неподдерживаемый формат файла
```
Unsupported file format: file.tiff (.tiff)
```
**Решение**: Файлы с неподдерживаемыми форматами будут пропущены. Миграция продолжится с остальными файлами.

### 5. Нехватка памяти
```
Fatal error: Allowed memory size exhausted
```
**Решение**: 
- Уменьшите `--batch-size`
- Увеличьте `memory_limit` в PHP
- Обрабатывайте по частям

## Проверка результатов

### SQL запросы для проверки
```sql
-- Количество обработанных записей
SELECT COUNT(*) FROM media WHERE collection_name = 'product-images';

-- Записи по продуктам
SELECT m.*, p.sku, p.name 
FROM media m 
JOIN products p ON m.model_id = p.id 
WHERE m.model_type = 'App\\Models\\Shop\\Product' 
ORDER BY m.created_at DESC 
LIMIT 10;

-- Проверка конверсий
SELECT file_name, generated_conversions 
FROM media 
WHERE collection_name = 'product-images' 
LIMIT 5;
```

### Проверка файлов в админке
После миграции изображения должны появиться в админ-панели Filament в разделе Products.

## Откат изменений

Если нужно откатить миграцию:

```sql
-- Удалить все записи медиа для продуктов
DELETE FROM media 
WHERE model_type = 'App\\Models\\Shop\\Product' 
AND collection_name = 'product-images';
```

```bash
# Удалить папку с файлами
rm -rf storage/app/public/media/
```

## Логирование

Все операции логируются в консоль. Для записи в файл:

```bash
php artisan migrate:old-media > migration.log 2>&1
``` 