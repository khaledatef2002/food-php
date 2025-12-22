INSERT INTO `website_settings` (`id`, `title`, `value`) VALUES (NULL, 'visa_qnb_merchant_id', ''), (NULL, 'visa_qnb_api_password', '');
DELETE FROM `website_settings` WHERE `title` = 'API_KEY' OR `title` = 'merchantID' OR `title` = 'secretKey';


INSERT INTO `website_settings` (`id`, `title`, `value`) VALUES (NULL, 'sunmi_print', '0');


INSERT INTO `website_settings` (`id`, `title`, `value`) VALUES (NULL, 'selected_payment_method_providor', '');
ALTER TABLE `food_orders` ADD `visa_providor` VARCHAR(255) NOT NULL AFTER `method`;
UPDATE `food_orders` SET `visa_providor`='qnb' WHERE `method`=1;
ALTER TABLE `visa_orders_req` ADD `visa_providor` VARCHAR(255) NOT NULL AFTER `total_order`;
UPDATE `visa_orders_req` SET `visa_providor`='qnb';

ALTER TABLE `food_orders` CHANGE `transaction_id` `transaction_id` VARCHAR(225) NOT NULL;


ALTER TABLE food_orders ADD INDEX idx_ordered_marked (ordered_date, marked);
-- Adding LIMIT

INSERT INTO `website_settings` (`id`, `title`, `value`) VALUES (NULL, 'paymob_hmac', ''), (NULL, 'paymob_secret_key', ''), (NULL, 'paymob_public_key', ''), (NULL, 'paymob_integration_id', '');

UPDATE `website_settings` SET `title` = 'paymob_iframe_id' WHERE `title` = 'paymob_public_key';

UPDATE website_settings SET title = 'paymob_public_key' WHERE title = 'paymob_iframe_id'