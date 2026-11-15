<?php
declare(strict_types=1);
namespace Ksfraser\FrontAccounting\HRM\Banks\Repository;
final class HRMBankLedgerRepository {
    private string $table = 'hrm_bank_ledger';
    public function findAll(): array {
        if (!function_exists('db_query') || !defined('TB_PREF')) { return array(); }
        $sql = "SELECT * FROM " . TB_PREF . $this->table . " ORDER BY transaction_date DESC, id DESC";
        $result = db_query($sql, 'Could not load bank ledger');
        if ($result === false) { return array(); }
        $rows = array();
        while (($row = db_fetch_assoc($result)) !== false && $row !== null) { $rows[] = $row; }
        return $rows;
    }
    public function findById(int $id): ?array {
        if (!function_exists('db_query') || !defined('TB_PREF')) { return null; }
        $sql = "SELECT * FROM " . TB_PREF . $this->table . " WHERE id = " . (int) $id;
        $result = db_query($sql, 'Could not load bank ledger entry');
        if ($result === false) { return null; }
        $row = db_fetch_assoc($result);
        return $row === false ? null : $row;
    }
    public function findByBank(string $bankId): array {
        if (!function_exists('db_query') || !defined('TB_PREF')) { return array(); }
        $sql = "SELECT * FROM " . TB_PREF . $this->table . " WHERE bank_id = " . $this->escape($bankId) . " ORDER BY transaction_date DESC";
        $result = db_query($sql, 'Could not load ledger entries by bank');
        if ($result === false) { return array(); }
        $rows = array();
        while (($row = db_fetch_assoc($result)) !== false && $row !== null) { $rows[] = $row; }
        return $rows;
    }
    public function findByEmployee(string $employeeId): array {
        if (!function_exists('db_query') || !defined('TB_PREF')) { return array(); }
        $sql = "SELECT * FROM " . TB_PREF . $this->table . " WHERE employee_id = " . $this->escape($employeeId) . " ORDER BY transaction_date DESC";
        $result = db_query($sql, 'Could not load ledger entries by employee');
        if ($result === false) { return array(); }
        $rows = array();
        while (($row = db_fetch_assoc($result)) !== false && $row !== null) { $rows[] = $row; }
        return $rows;
    }
    public function save(array $data): int {
        if (!function_exists('db_query') || !defined('TB_PREF')) { return 0; }
        $sql = "INSERT INTO " . TB_PREF . $this->table . " (
                bank_id, employee_id, transaction_type, amount, balance_after,
                transaction_date, reference, notes)
            VALUES ("
            . $this->escape($data['bank_id'] ?? '') . ", "
            . $this->escape($data['employee_id'] ?? '') . ", "
            . $this->escape($data['transaction_type'] ?? '') . ", "
            . $this->escape($data['amount'] ?? '0.00') . ", "
            . $this->escape($data['balance_after'] ?? '0.00') . ", "
            . $this->escape($data['transaction_date'] ?? '') . ", "
            . $this->escape($data['reference'] ?? '') . ", "
            . $this->escape($data['notes'] ?? '') . ")";
        db_query($sql, 'Could not save bank ledger entry');
        return db_insert_id();
    }
    public function update(int $id, array $data): void {
        if (!function_exists('db_query') || !defined('TB_PREF')) { return; }
        $sets = [];
        foreach (['bank_id', 'employee_id', 'transaction_type', 'amount', 'balance_after', 'transaction_date', 'reference', 'notes'] as $col) {
            if (array_key_exists($col, $data)) { $sets[] = "$col = " . $this->escape($data[$col]); }
        }
        if (empty($sets)) { return; }
        $sql = "UPDATE " . TB_PREF . $this->table . " SET " . implode(', ', $sets) . " WHERE id = " . (int) $id;
        db_query($sql, 'Could not update bank ledger entry');
    }
    public function delete(int $id): void {
        if (!function_exists('db_query') || !defined('TB_PREF')) { return; }
        $sql = "DELETE FROM " . TB_PREF . $this->table . " WHERE id = " . (int) $id;
        db_query($sql, 'Could not delete bank ledger entry');
    }
    private function escape($value): string {
        return function_exists('db_escape') ? db_escape($value) : addslashes((string) $value);
    }
    private function intVal($value): int {
        return (int) $value;
    }
}
