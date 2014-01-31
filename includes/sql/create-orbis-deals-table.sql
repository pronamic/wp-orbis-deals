CREATE TABLE IF NOT EXISTS orbis_deals (
	id BIGINT(16) UNSIGNED NOT NULL AUTO_INCREMENT,
  company_id BIGINT(16) UNSIGNED DEFAULT NULL,
  post_id BIGINT(20) UNSIGNED DEFAULT NULL,
  price FLOAT NULL,
  status TINYINT(1) DEFAULT 0,

  PRIMARY KEY  (id),
  UNIQUE KEY post_id (post_id),
  KEY company_id (company_id)
)
ENGINE = InnoDB 
CHARSET = utf8 
COLLATE = utf8_unicode_ci;