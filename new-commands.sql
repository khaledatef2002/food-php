ALTER TABLE `social_media` ADD `type` VARCHAR(255) NOT NULL AFTER `sort`;
ALTER TABLE `social_media` DROP `img_url`;