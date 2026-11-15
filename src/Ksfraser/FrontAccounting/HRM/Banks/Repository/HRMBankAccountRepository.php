<?php
declare(strict_types=1);
namespace Ksfraser\FrontAccounting\HRM\Banks\Repository;
final class HRMBankAccountRepository {
    private string $table = 'hrm_bank_accounts';
    public function findAll(): array {
        if (!function_exists('db_query') || !defined('TB_PREF')) { return array(); }
        $sql = "SELECT * FROM " . TB_PREF . $this->table . " ORDER BY employee_id ASC, bank_type ASC";
        $result = db_query($sql, 'Could not load bank accounts');
        if ($result === false) { return array(); }
        $rows = array();
        while (($row = db_fetch_assoc($result)) !== false && $row !== null) { $rows[] = $row; }
        return $rows;
    }
    public function findById(int $id): ?array {
        if (!function_exists('db_query') || !defined('TB_PREF')) { return null; }
        $sql = "SELECT * FROM " . TB_PREF . $this->table . " WHERE id = " . (int) $id;
        $result = db_query($sql, 'Could not load bank account');
        if ($result === false) { return null; }
        $row = db_fetch_assoc($result);
        return $row === false ? null : $row;
    }
    public function findByEmployee(string $employeeId): array {
        if (!function_exists('db_query') || !defined('TB_PREF')) { return array(); }
        $sql = "SELECT * FROM " . TB_PREF . $this->table . " WHERE employee_id = " . $this->escape($employeeId) . " ORDER BY bank_type ASC";
        $result = db_query($sql, 'Could not load bank accounts by employee');
        if ($result === false) { return array(); }
        $rows = array();
        while (($row = db_fetch_assoc($result)) !== false && $row !== null) { $rows[] = $row; }
        return $rows;
    }
    public function save(array $data): int {
        if (!function_exists('db_query') || !defined('TB_PREF')) { return 0; }
        $sql = "INSERT INTO " . TB_PREF . $this->table . " (
                employee_id, bank_type, bank_name, current_balance, available_balance,
                accrued_balance, used_balance, expires_on, is_active)
            VALUES ("
            . $this->escape($data['employee_id'] ?? '') . ", "
            . $this->escape($data['bank_type'] ?? '') . ", "
            . $this->escape($data['bank_name'] ?? '') . ", "
            . $this->escape($data['current_balance'] ?? '0.00') . ", "
            . $this->escape($data['available_balance'] ?? '0.00') . ", "
            . $this->escape($data['accrued_balance'] ?? '0.00') . ", "
            . $this->escape($data['used_balance'] ?? '0.00') . ", "
            . $this->escape($data['expires_on'] ?? '') . ", "
            . (isset($data['is_active']) ? (int)$data['is_active'] : 1) . ")";
        db_query($sql, 'Could not save bank account');
        return db_insert_id();
    }
    public function update(int $id, array $data): void {
        if (!function_exists('db_query') || !defined('TB_PREF')) { return; }
        $sets = [];
        foreach (['employee_id', 'bank_type', 'bank_name', 'current_balance', 'available_balance', 'accrued_balance', 'used_balance', 'expires_on', 'is_active'] as $col) {
            if (array_key_exists($col, $data)) {
                $val = ($col === 'is_active') ? (int)$data[$col] : $this->escape($data[$col]);
                $sets[] = "$col = $val";
            }
        }
        if (empty($sets)) { return; }
        $sql = "UPDATE " . TB_PREF . $this->table . " SET " . implode(', ', $sets) . " WHERE id = " . (int) $id;
        db_query($sql, 'Could not update bank account');
    }
    public function delete(int $id): void {
        if (!function_exists('db_query') || !defined('TB_PREF')) { return; }
        $sql = "DELETE FROM " . TB_PREF . $this->table . " WHERE id = " . (int) $id;
        db_query($sql, 'Could not delete bank account');
    }
    private function escape($value): string {
        return function_exists('db_escape') ? db_escape($value) : addslashes((string) $value);
    }
    private function intVal($value): int {
        return (int) $value;
    }
}
