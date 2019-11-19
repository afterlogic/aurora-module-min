
CREATE TABLE IF NOT EXISTS `%PREFIX%min_hashes` (
  `hash_id` varchar(32) NOT NULL DEFAULT '',
  `user_id` BIGINT(64) NULL DEFAULT NULL,
  `hash` varchar(20) NOT NULL DEFAULT '',
  `data` text,
  KEY `%PREFIX%min_hash_index` (`hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


SELECT count(*) INTO @exist
  FROM information_schema.columns 
    WHERE table_schema = database() AND COLUMN_NAME = 'user_id' AND table_name = 'au_min_hashes';

SET @query = IF(@exist <= 0, 'ALTER TABLE `au_min_hashes` ADD COLUMN `user_id` BIGINT(64) NULL DEFAULT NULL AFTER `hash_id`', 'select 1 status');

prepare stmt FROM @query;

EXECUTE stmt;
