-- SQL скрипт для создания таблицы old_media
-- Если таблица еще не существует

CREATE TABLE IF NOT EXISTS `old_media` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `product_id` int(11) NOT NULL,
    `media_id` int(11) NOT NULL,
    `sku` varchar(255) NOT NULL,
    `name` varchar(255) NOT NULL,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_product_id` (`product_id`),
    KEY `idx_media_id` (`media_id`),
    KEY `idx_sku` (`sku`),
    KEY `idx_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Пример вставки данных (на основе скриншота)
INSERT INTO `old_media` (`product_id`, `media_id`, `sku`, `name`) VALUES
(2, 1969, '21069', '1969f29e-6da5-4dcf-8855-bc1f83cccc04.jpeg'),
(105, 4727, '598026', '04727a4b-9d30-45f4-a706-6e80f49f95c4.png'),
(111, 4713, '716410', '4713d71e-0725-4fe5-8419-5cb459e6aa13.png'),
(115, 398, 'MQ052RS/A', '398a8034-4be2-4c1c-a3cc-0b1a36871f47.png');

-- Проверка данных
SELECT COUNT(*) as total_records FROM old_media;
SELECT product_id, media_id, sku, name FROM old_media LIMIT 10; 