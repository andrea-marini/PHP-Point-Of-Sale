ALTER TABLE  `phppos_customers` ADD  `zone` VARCHAR( 255 ) NOT NULL AFTER  `taxable`;
ALTER TABLE  `phppos_sales` ADD  `delivery_date` DATE NOT NULL AFTER  `sale_time` ,
ADD  `delivery_time` VARCHAR( 255 ) NOT NULL AFTER  `delivery_date`;
ALTER TABLE  `phppos_sales` ADD  `balance` DOUBLE( 15, 2 ) NOT NULL AFTER  `delivery_time`;
ALTER TABLE phppos_sales_items_taxes DROP FOREIGN KEY phppos_sales_items_taxes_ibfk_2;
ALTER TABLE `phppos_sales_items_taxes` ADD CONSTRAINT `phppos_sales_items_taxes_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `phppos_items` (`item_id`);