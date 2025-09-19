INSERT INTO `main_page_icons`(`icon_name`, `icon_active`) VALUES ('phone', 0);
INSERT INTO `main_page_icons`(`icon_name`, `icon_active`) VALUES ('whatsapp', 0);
UPDATE `main_page_icons` SET id = 1 WHERE icon_name = 'order';
UPDATE `main_page_icons` SET id = 2 WHERE icon_name = 'phone';
UPDATE `main_page_icons` SET id = 3 WHERE icon_name = 'menu';

DELETE FROM `main_page_icons`;
INSERT INTO `main_page_icons`(`icon_name`, `icon_active`) VALUES ('order', "1");
INSERT INTO `main_page_icons`(`icon_name`, `icon_active`) VALUES ('menu', "1");
INSERT INTO `main_page_icons`(`icon_name`, `icon_active`) VALUES ('phone', 0);
INSERT INTO `main_page_icons`(`icon_name`, `icon_active`) VALUES ('whatsapp', 0);
INSERT INTO `main_page_icons`(`icon_name`, `icon_active`) VALUES ('social', 0);