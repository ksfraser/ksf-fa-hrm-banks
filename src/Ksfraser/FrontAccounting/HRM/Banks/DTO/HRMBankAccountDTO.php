<?php
declare(strict_types=1);
namespace Ksfraser\FrontAccounting\HRM\Banks\DTO;
final class HRMBankAccountDTO {
    public $id;
    public $employee_id;
    public $employee_name;
    public $bank_type;
    public $bank_type_name;
    public $bank_name;
    public $current_balance;
    public $available_balance;
    public $accrued_balance;
    public $used_balance;
    public $expires_on;
    public $is_active;
    public $created_at;
    public function __construct(
        int $id,
        string $employee_id = '',
        string $employee_name = '',
        string $bank_type = '',
        string $bank_type_name = '',
        string $bank_name = '',
        string $current_balance = '0.00',
        string $available_balance = '0.00',
        string $accrued_balance = '0.00',
        string $used_balance = '0.00',
        string $expires_on = '',
        bool $is_active = true,
        string $created_at = ''
    ) {
        $this->id = $id;
        $this->employee_id = $employee_id;
        $this->employee_name = $employee_name;
        $this->bank_type = $bank_type;
        $this->bank_type_name = $bank_type_name;
        $this->bank_name = $bank_name;
        $this->current_balance = $current_balance;
        $this->available_balance = $available_balance;
        $this->accrued_balance = $accrued_balance;
        $this->used_balance = $used_balance;
        $this->expires_on = $expires_on;
        $this->is_active = $is_active;
        $this->created_at = $created_at;
    }
    public function getDisplayLabel(): string {
        $parts = array();
        if ($this->employee_name !== '') { $parts[] = $this->employee_name; }
        if ($this->bank_type_name !== '') { $parts[] = $this->bank_type_name; }
        if ($this->current_balance !== '' && $this->current_balance !== '0.00') { $parts[] = $this->current_balance; }
        return $parts === [] ? _('Bank Account #') . (string) $this->id : implode(' — ', $parts);
    }
    public function toArray(): array {
        return array(
            'id' => $this->id,
            'employee_id' => $this->employee_id,
            'employee_name' => $this->employee_name,
            'bank_type' => $this->bank_type,
            'bank_type_name' => $this->bank_type_name,
            'bank_name' => $this->bank_name,
            'current_balance' => $this->current_balance,
            'available_balance' => $this->available_balance,
            'accrued_balance' => $this->accrued_balance,
            'used_balance' => $this->used_balance,
            'expires_on' => $this->expires_on,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
        );
    }
    public static function fromArray(array $data): self {
        return new self(
            (int) ($data['id'] ?? 0),
            (string) ($data['employee_id'] ?? ''),
            (string) ($data['employee_name'] ?? ''),
            (string) ($data['bank_type'] ?? ''),
            (string) ($data['bank_type_name'] ?? ''),
            (string) ($data['bank_name'] ?? ''),
            (string) ($data['current_balance'] ?? '0.00'),
            (string) ($data['available_balance'] ?? '0.00'),
            (string) ($data['accrued_balance'] ?? '0.00'),
            (string) ($data['used_balance'] ?? '0.00'),
            (string) ($data['expires_on'] ?? ''),
            !empty($data['is_active']),
            (string) ($data['created_at'] ?? '')
        );
    }
}
