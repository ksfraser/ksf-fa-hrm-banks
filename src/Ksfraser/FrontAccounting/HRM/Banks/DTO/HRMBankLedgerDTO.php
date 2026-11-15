<?php
declare(strict_types=1);
namespace Ksfraser\FrontAccounting\HRM\Banks\DTO;
final class HRMBankLedgerDTO {
    public $id;
    public $bank_id;
    public $bank_name;
    public $employee_id;
    public $employee_name;
    public $transaction_type;
    public $transaction_type_name;
    public $amount;
    public $balance_after;
    public $transaction_date;
    public $reference;
    public $notes;
    public $created_at;
    public function __construct(
        int $id,
        string $bank_id = '',
        string $bank_name = '',
        string $employee_id = '',
        string $employee_name = '',
        string $transaction_type = '',
        string $transaction_type_name = '',
        string $amount = '0.00',
        string $balance_after = '0.00',
        string $transaction_date = '',
        string $reference = '',
        string $notes = '',
        string $created_at = ''
    ) {
        $this->id = $id;
        $this->bank_id = $bank_id;
        $this->bank_name = $bank_name;
        $this->employee_id = $employee_id;
        $this->employee_name = $employee_name;
        $this->transaction_type = $transaction_type;
        $this->transaction_type_name = $transaction_type_name;
        $this->amount = $amount;
        $this->balance_after = $balance_after;
        $this->transaction_date = $transaction_date;
        $this->reference = $reference;
        $this->notes = $notes;
        $this->created_at = $created_at;
    }
    public function getDisplayLabel(): string {
        $parts = array();
        if ($this->employee_name !== '') { $parts[] = $this->employee_name; }
        if ($this->bank_name !== '') { $parts[] = $this->bank_name; }
        if ($this->transaction_type_name !== '') { $parts[] = $this->transaction_type_name; }
        if ($this->amount !== '' && $this->amount !== '0.00') { $parts[] = $this->amount; }
        return $parts === [] ? _('Ledger Entry #') . (string) $this->id : implode(' — ', $parts);
    }
    public function toArray(): array {
        return array(
            'id' => $this->id,
            'bank_id' => $this->bank_id,
            'bank_name' => $this->bank_name,
            'employee_id' => $this->employee_id,
            'employee_name' => $this->employee_name,
            'transaction_type' => $this->transaction_type,
            'transaction_type_name' => $this->transaction_type_name,
            'amount' => $this->amount,
            'balance_after' => $this->balance_after,
            'transaction_date' => $this->transaction_date,
            'reference' => $this->reference,
            'notes' => $this->notes,
            'created_at' => $this->created_at,
        );
    }
    public static function fromArray(array $data): self {
        return new self(
            (int) ($data['id'] ?? 0),
            (string) ($data['bank_id'] ?? ''),
            (string) ($data['bank_name'] ?? ''),
            (string) ($data['employee_id'] ?? ''),
            (string) ($data['employee_name'] ?? ''),
            (string) ($data['transaction_type'] ?? ''),
            (string) ($data['transaction_type_name'] ?? ''),
            (string) ($data['amount'] ?? '0.00'),
            (string) ($data['balance_after'] ?? '0.00'),
            (string) ($data['transaction_date'] ?? ''),
            (string) ($data['reference'] ?? ''),
            (string) ($data['notes'] ?? ''),
            (string) ($data['created_at'] ?? '')
        );
    }
}
