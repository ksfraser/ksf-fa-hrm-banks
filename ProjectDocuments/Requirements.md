# Business Requirements — ksf_FA_HRMBanks

**Package:** `ksfraser/ksf-fa-hrm-banks`
**Version:** `1.0.0`
**Namespace:** `Ksfraser\FrontAccounting\HRM\Banks\*`
**Module sequence:** HB (HRM Banks)

---

## BR-HB-001 — Bank account management

**Status:** IMPLEMENTED

The system SHALL provide `ksf_FA_HRMBanks` as the owning module for HRM bank accounts (PTO, VAC, STAT, SICK, education allowance, travel allowance, extended health care, etc.) as running GL-like accounts.

1. Bank accounts SHALL have bank_type, bank_name, current_balance, available_balance, accrued_balance, used_balance, expires_on, and active status.
2. Bank accounts SHALL reference `0_ksf_code_master` with `code_type = 'hrm_bank_type'` for the bank type.
3. Bank accounts SHALL be listable, creatable, editable, and deactivatable.
4. Available balance SHALL be computed as current_balance - used_balance.
5. Bank types SHALL include leave types (PTO, VAC, STAT, SICK) and benefits types (EDU education allowance, TRV travel allowance, EHC extended health care).

---

## BR-HB-002 — Bank ledger

**Status:** IMPLEMENTED

Bank ledger SHALL track all accruals and usages as running transactions.

1. Ledger entries SHALL have transaction_type, amount, balance_after, transaction_date, reference, and notes.
2. Transaction types SHALL reference `0_ksf_code_master` with `code_type = 'hrm_bank_transaction_type'`.
3. Ledger entries SHALL be listable by bank and by employee.

---

## BR-HB-003 — Expiration rules

**Status:** IMPLEMENTED

Bank accounts SHALL support expiration rules for unused balances.

1. Bank accounts SHALL have an `expires_on` date.
2. Expired balances SHALL be visible in the UI.
3. Workflow rules SHALL be configurable for expiration handling.

---

## BR-HB-004 — Resolved DTOs for RelatedTo

**Status:** IMPLEMENTED

HRMBanks SHALL build resolved display data for RelatedTo display for both `hrm_bank_account` and `hrm_bank_ledger` types.

1. Resolved data SHALL wrap a `RelatedRecordDto` with resolved fields, overridable labels, and a URL.
2. `fieldMap()` SHALL return the field name => display label mapping.
3. `listAll()` SHALL return resolved data for summary table display.

