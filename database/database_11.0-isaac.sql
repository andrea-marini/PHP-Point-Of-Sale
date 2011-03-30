ALTER TABLE  `phppos_customers` ADD  `zone` VARCHAR( 255 ) NOT NULL AFTER  `taxable`;
ALTER TABLE  `phppos_sales` ADD  `delivery_date` DATE NOT NULL AFTER  `sale_time` ,
ADD  `delivery_time` VARCHAR( 255 ) NOT NULL AFTER  `delivery_date`;
ALTER TABLE  `phppos_sales` ADD  `balance` DOUBLE( 15, 2 ) NOT NULL AFTER  `delivery_time`;