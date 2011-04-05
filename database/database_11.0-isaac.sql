ALTER TABLE  `phppos_customers` 
ADD  `zone` VARCHAR( 255 ) NOT NULL AFTER  `taxable`,
ADD  `cc_number` VARCHAR( 255 ) NOT NULL AFTER  `zone`,
ADD  `cc_expiration` VARCHAR( 255 ) NOT NULL AFTER  `cc_number` ,
ADD  `cc_security_code` VARCHAR( 255 ) NOT NULL AFTER  `expiration` ,
ADD  `billing_zip` VARCHAR( 255 ) NOT NULL AFTER  `cc_security_code`;
ALTER TABLE  `phppos_sales` ADD  `delivery_date` DATE NOT NULL AFTER  `sale_time` ,
ADD  `delivery_time` VARCHAR( 255 ) NOT NULL AFTER  `delivery_date`;
ALTER TABLE  `phppos_sales` ADD  `balance` DOUBLE( 15, 2 ) NOT NULL AFTER  `delivery_time`;
ALTER TABLE phppos_sales_items_taxes DROP FOREIGN KEY phppos_sales_items_taxes_ibfk_2;
ALTER TABLE `phppos_sales_items_taxes` ADD CONSTRAINT `phppos_sales_items_taxes_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `phppos_items` (`item_id`);