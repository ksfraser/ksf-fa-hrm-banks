-- ksf_FA_HRMBanks: HRM bank accounts (PTO, VAC, STAT, SICK, etc.)
-- Running GL-like bank accounts with accruals, usage tracking, expiration rules.

CREATE TABLE IF NOT EXISTS `0_hrm_bank_accounts` (
  `id`            INT(11) NOT NULL AUTO_INCREMENT,
  `employee_id`   VARCHAR(50)  NOT NULL DEFAULT '' COMMENT 'Ref to crm_persons.id or employee ref code',
  `bank_type`     VARCHAR(30)  NOT NULL DEFAULT '' COMMENT 'Ref code to 0_ksf_code_master.code_type = hrm_bank_type',
  `bank_name`     VARCHAR(100) NOT NULL DEFAULT '' COMMENT 'Human-readable bank name',
  `current_balance` DECIMAL(15,2) NOT NULL DEFAULT 0.00 COMMENT 'Current available balance',
  `available_balance` DECIMAL(15,2) NOT NULL DEFAULT 0.00 COMMENT 'Available balance (current - used)',
  `accrued_balance` DECIMAL(15,2) NOT NULL DEFAULT 0.00 COMMENT 'Total accrued balance',
  `used_balance`   DECIMAL(15,2) NOT NULL DEFAULT 0.00 COMMENT 'Total used balance',
  `expires_on`     DATE         DEFAULT NULL COMMENT 'Expiration date for unused balance',
  `is_active`      TINYINT(1)   NOT NULL DEFAULT 1,
  `created_at`     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_employee_id` (`employee_id`),
  KEY `idx_bank_type` (`bank_type`),
  KEY `idx_expires_on` (`expires_on`),
  KEY `idx_is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `0_hrm_bank_ledger` (
  `id`              INT(11) NOT NULL AUTO_INCREMENT,
  `bank_id`         INT(11) NOT NULL COMMENT 'FK to 0_hrm_bank_accounts.id',
  `employee_id`     VARCHAR(50)  NOT NULL DEFAULT '' COMMENT 'Ref to crm_persons.id',
  `transaction_type` VARCHAR(30)  NOT NULL DEFAULT '' COMMENT 'Ref code to 0_ksf_code_master.code_type = hrm_bank_transaction_type',
  `amount`           DECIMAL(15,2) NOT NULL DEFAULT 0.00 COMMENT 'Transaction amount (positive=accrue, negative=use)',
  `balance_after`    DECIMAL(15,2) NOT NULL DEFAULT 0.00 COMMENT 'Running balance after transaction',
  `transaction_date` DATE         NOT NULL COMMENT 'Transaction date',
  `reference`        VARCHAR(100) DEFAULT NULL COMMENT 'Reference document/note',
  `notes`            TEXT COMMENT 'Free-form notes',
  `created_at`       TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_bank_id` (`bank_id`),
  KEY `idx_employee_id` (`employee_id`),
  KEY `idx_transaction_type` (`transaction_type`),
  KEY `idx_transaction_date` (`transaction_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
