ALTER TABLE `kaonekad`.`oc_order`
ADD COLUMN `shipping_collector_id` INT(11) UNSIGNED NOT NULL DEFAULT 0 AFTER `shipping_code`;
