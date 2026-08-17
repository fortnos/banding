-- Gmail Banding - schema (MySQL / MariaDB)

CREATE TABLE IF NOT EXISTS emails (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  email VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_emails_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS appeals (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  email_id INT UNSIGNED NOT NULL,
  status ENUM('pending','opened','failed') NOT NULL DEFAULT 'pending',
  ip_address VARCHAR(45) DEFAULT NULL,
  opened_at DATETIME DEFAULT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_appeals_status (status),
  CONSTRAINT fk_appeals_email FOREIGN KEY (email_id) REFERENCES emails (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
