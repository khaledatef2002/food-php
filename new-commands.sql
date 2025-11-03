INSERT INTO `website_settings` (`id`, `title`, `value`) VALUES (NULL, 'visa_qnb_merchant_id', ''), (NULL, 'visa_qnb_api_password', '');
DELETE FROM `website_settings` WHERE `title` = 'API_KEY' OR `title` = 'merchantID' OR `title` = 'secretKey';


INSERT INTO `website_settings` (`id`, `title`, `value`) VALUES (NULL, 'sunmi_print', '0');
