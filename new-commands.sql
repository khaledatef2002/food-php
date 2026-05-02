ALTER TABLE `food_branches` ADD `active` BOOLEAN NOT NULL DEFAULT TRUE AFTER `branch_name`;

ALTER TABLE `food_order_cart` ADD `notes` TEXT NULL AFTER `item_size_name`;

ALTER TABLE `visa_cart_req` ADD `notes` TEXT null AFTER `item_size_name`;

INSERT INTO `website_settings`(`title`, `value`) VALUES ('allow-item-notes', '0')