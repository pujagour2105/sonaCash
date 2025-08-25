ALTER TABLE `billing_details`
	ADD COLUMN `gross_wt` DECIMAL(20,6) NOT NULL  AFTER `quantity`;

ALTER TABLE `sale_invoice_details`
	ADD COLUMN `gross_wt` DECIMAL(20,6) NOT NULL  AFTER `quantity`;



ALTER TABLE `inventory_detail`
	CHANGE COLUMN `status` `status` INT(5) NOT NULL DEFAULT '0' COMMENT '0=Active/Not Recived, 1=Active/Recived, 2=Recived/Removed' AFTER `total_amount`;

ALTER TABLE `valuation`
	ADD COLUMN `branch_serial_num` INT(11) NULL DEFAULT NULL AFTER `customer_id`;

--  09-07-2025
ALTER TABLE `sale_invoice`
	ADD COLUMN `cash_amount` INT(11) NULL AFTER `branch_id`,
	ADD COLUMN `online_amount` INT(11) NULL AFTER `cash_amount`;

ALTER TABLE `billing`
	ADD COLUMN `cash_amount` INT(11) NULL AFTER `branch_id`,
	ADD COLUMN `online_amount` INT(11) NULL AFTER `cash_amount`;

-- 19-07-2025

ALTER TABLE `branch`
	ADD COLUMN `sale_invoice_no` INT NOT NULL AFTER `gst_no`,
	ADD COLUMN `purchase_invoice_no` INT NOT NULL AFTER `sale_invoice_no`;


-- 21-07-2025
ALTER TABLE `billing`
	ADD COLUMN `round_type` TINYINT(5)  NULL  AFTER `online_amount`,
	ADD COLUMN `round_value` DECIMAL(5,2)  NULL AFTER `round_type`;

ALTER TABLE `sale_invoice`
	ADD COLUMN `round_type` TINYINT(5)  NULL  AFTER `online_amount`,
	ADD COLUMN `round_value` DECIMAL(5,2)  NULL AFTER `round_type`;

ALTER TABLE `sale_invoice`
	CHANGE COLUMN `amount` `amount` DECIMAL(20,6) NOT NULL AFTER `round_value`,
	CHANGE COLUMN `balance_amount` `balance_amount` DECIMAL(20,6)  NULL AFTER `amount`;

ALTER TABLE `billing`
	CHANGE COLUMN `amount` `amount` DECIMAL(20,6) NOT NULL AFTER `round_value`,
	CHANGE COLUMN `balance_amount` `balance_amount` DECIMAL(20,6) NULL AFTER `amount`;

-- 07-08-2025

ALTER TABLE `valuation`
	DROP COLUMN `branch_serial_num`;


ALTER TABLE `valuation`
	CHANGE COLUMN `image` `image` TEXT NULL COLLATE 'latin1_swedish_ci' AFTER `customer_id`,
	CHANGE COLUMN `image2` `image2` TEXT NULL COLLATE 'latin1_swedish_ci' AFTER `image`,
	ADD COLUMN `client_image` TEXT NULL AFTER `image2`,
	ADD COLUMN `jewellery_image` TEXT NULL AFTER `client_image`;


