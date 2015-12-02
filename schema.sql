DROP TABLE IF EXISTS `reports`;
DROP TABLE IF EXISTS `applications`;
DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `username` VARCHAR(32) NOT NULL,
  `password` VARCHAR(64) NOT NULL,
  `created_at` DATETIME DEFAULT 0,
  `updated_at` DATETIME DEFAULT 0,
  UNIQUE KEY (`username`)
) CHARSET utf8 ENGINE InnoDB;

CREATE TABLE `applications` (
  `id` BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `package_name` VARCHAR(255) NOT NULL,
  `title` VARCHAR(128) NOT NULL,
  `token` VARCHAR(24) NOT NULL,
  `created_at` DATETIME DEFAULT 0,
  `updated_at` DATETIME DEFAULT 0,
  UNIQUE KEY (`package_name`),
  UNIQUE KEY (`title`),
  UNIQUE KEY (`token`)
) CHARSET utf8 ENGINE InnoDB;

CREATE TABLE `reports` (
  `id` BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `application_id` BIGINT UNSIGNED,
# ACRA >>
  `android_version` VARCHAR(5) NOT NULL,
  `application_log` TEXT NOT NULL,
  `app_version_code` INT(11) NOT NULL,
  `app_version_name` VARCHAR(32) NOT NULL,
  `available_mem_size` INT(11) NOT NULL,
  `brand` VARCHAR(128) NOT NULL,
  `build` TEXT NOT NULL,
  `build_config` TEXT NOT NULL,
  `crash_configuration` TEXT NOT NULL,
  `custom_data` TEXT NULL,
  `device_features` TEXT NOT NULL,
  `device_id` VARCHAR(64) NULL,
  `display` TEXT NOT NULL,
  `dropbox` TEXT NULL,
  `dumpsys_meminfo` TEXT NULL,
  `environment` TEXT NOT NULL,
  `eventslog` LONGTEXT NULL,
  `file_path` VARCHAR(255) NOT NULL,
  `initial_configuration` TEXT NULL,
  `installation_id` VARCHAR(64) NOT NULL,
  `is_silent` CHAR(5) NOT NULL,
  `logcat` LONGTEXT NULL,
  `media_codec_list` LONGTEXT NULL,
  `package_name` VARCHAR(255) NULL,
  `phone_model` VARCHAR(128) NOT NULL,
  `product` VARCHAR(128) NOT NULL,
  `radiolog` LONGTEXT NULL,
  `report_id` VARCHAR(64) NULL,
  `settings_global` LONGTEXT NULL,
  `settings_secure` LONGTEXT NULL,
  `settings_system` LONGTEXT NULL,
  `shared_preferences` LONGTEXT NOT NULL,
  `stack_trace` TEXT NOT NULL,
  `thread_details` TEXT NOT NULL,
  `total_mem_size` INT(11) NOT NULL,
  `user_app_start_date` VARCHAR(128) NULL,
  `user_comment` TEXT NOT NULL,
  `user_crash_date` VARCHAR(128) NULL,
  `user_email` VARCHAR(128) NULL,
# << ACRA
  `checksum` VARCHAR(32) NOT NULL,
  `exception` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT 0,
  `updated_at` TIMESTAMP DEFAULT 0 ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`application_id`) REFERENCES `applications`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  FOREIGN KEY (`package_name`) REFERENCES `applications`(`package_name`) ON DELETE CASCADE ON UPDATE RESTRICT
) CHARSET utf8 ENGINE InnoDB;
