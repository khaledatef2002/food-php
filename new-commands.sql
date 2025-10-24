ALTER TABLE `food_orders` ADD `order_type` ENUM('delivery','branch','','') NOT NULL AFTER `client_branch`;
ALTER TABLE `visa_orders_req` ADD `order_type` ENUM('delivery','branch','','') NOT NULL AFTER `client_branch`;
INSERT INTO `website_settings` (`id`, `title`, `value`) VALUES (NULL, 'order_from_branch', '0')