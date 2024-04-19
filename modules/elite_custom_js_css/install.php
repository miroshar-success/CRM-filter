<?php

defined('BASEPATH') or exit('No direct script access allowed');
/*
 * Create Table in Configure Database
 */

if (!$CI->db->table_exists(ElITE_CUSTOM_JS_CSS_TABLE_NAME)) {
  $CI->db->query('CREATE TABLE `' . ElITE_CUSTOM_JS_CSS_TABLE_NAME . "` (
  `id` int(11) NOT NULL,
  `name` varchar(191) NOT NULL,
  `code` LONGTEXT NOT NULL,
  `description` LONGTEXT NULL,
  `area_type` varchar(30) NOT NULL,
  `code_type` varchar(20) NULL,
  `status` ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `staff_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL,
  INDEX (name,status,staff_id,area_type,code_type,created_by,created_at desc),
  FOREIGN KEY (staff_id) REFERENCES " . db_prefix() . "staff(staffid) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');

  $CI->db->query('ALTER TABLE `' . ElITE_CUSTOM_JS_CSS_TABLE_NAME . '`
  ADD PRIMARY KEY (`id`);');

  $CI->db->query('ALTER TABLE `' . ElITE_CUSTOM_JS_CSS_TABLE_NAME . '`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1');
}

/**
 * add new field in existing table
 */
if ($CI->db->table_exists(ElITE_CUSTOM_JS_CSS_TABLE_NAME)) {
  if (!$CI->db->field_exists('code_view', ElITE_CUSTOM_JS_CSS_TABLE_NAME)) {
    $CI->db->query('ALTER TABLE `' . ElITE_CUSTOM_JS_CSS_TABLE_NAME . '` ADD code_view varchar(50) NOT NULL AFTER code_type;');
  }
}