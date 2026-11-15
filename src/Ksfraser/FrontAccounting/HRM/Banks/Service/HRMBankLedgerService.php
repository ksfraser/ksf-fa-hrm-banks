<?php
declare(strict_types=1);
namespace Ksfraser\FrontAccounting\HRM\Banks\Service;
use Ksfraser\FrontAccounting\HRM\Banks\DTO\HRMBankLedgerDTO;
use Ksfraser\FrontAccounting\HRM\Banks\Repository\HRMBankLedgerRepository;
use ksfraser\RelatedTo\DTO\RelatedRecordDto;
final class HRMBankLedgerService {
    private $repo;
    public function __construct(?HRMBankLedgerRepository $repo = null) { $this->repo = $repo ?? new HRMBankLedgerRepository(); }
    public function resolveById(int $id, ?RelatedRecordDto $dto = null): array {
        $row = $this->repo->findById($id);
        if ($row === null) {
            return array('dto' => new RelatedRecordDto('hrm_bank_ledger', '0', 'hrm_bank_ledger', '0', null), 'fields' => $this->fieldMap(), 'labels' => $this->labelMap(), 'url' => '#', 'label' => _('Ledger Entry #') . (string) $id);
        }
        $l = HRMBankLedgerDTO::fromArray($row);
        $dto = $dto ?? new RelatedRecordDto('hrm_bank_ledger', (string) $l->id, 'hrm_bank_ledger', (string) $l->id, null);
        return array(
            'dto' => $dto,
            'fields' => array(
                'transaction_type' => $l->transaction_type,
                'amount' => $l->amount,
                'balance_after' => $l->balance_after,
                'transaction_date' => $l->transaction_date,
                'reference' => $l->reference,
                'notes' => $l->notes,
            ),
            'labels' => $this->labelMap(),
            'url' => '/modules/ksf_FA_HRMBanks/pages/bank_ledger_form.php?action=edit&ledger_id=' . $l->id,
            'label' => $l->getDisplayLabel(),
        );
    }
    public function listAll(): array {
        $rows = $this->repo->findAll();
        $results = array();
        foreach ($rows as $row) {
            $l = HRMBankLedgerDTO::fromArray($row);
            $results[] = array(
                'id' => $l->id,
                'dto' => new RelatedRecordDto('hrm_bank_ledger', (string) $l->id, 'hrm_bank_ledger', (string) $l->id, null),
                'fields' => array(
                    'transaction_type' => $l->transaction_type,
                    'amount' => $l->amount,
                    'balance_after' => $l->balance_after,
                    'transaction_date' => $l->transaction_date,
                    'reference' => $l->reference,
                    'notes' => $l->notes,
                ),
                'labels' => $this->labelMap(),
                'url' => '/modules/ksf_FA_HRMBanks/pages/bank_ledger_form.php?action=edit&ledger_id=' . $l->id,
                'label' => $l->getDisplayLabel(),
            );
        }
        return $results;
    }
    public function listByBank(string $bankId): array {
        $rows = $this->repo->findByBank($bankId);
        $results = array();
        foreach ($rows as $row) {
            $l = HRMBankLedgerDTO::fromArray($row);
            $results[] = array(
                'id' => $l->id,
                'dto' => new RelatedRecordDto('hrm_bank_ledger', (string) $l->id, 'hrm_bank_ledger', (string) $l->id, null),
                'fields' => array(
                    'transaction_type' => $l->transaction_type,
                    'amount' => $l->amount,
                    'balance_after' => $l->balance_after,
                    'transaction_date' => $l->transaction_date,
                    'reference' => $l->reference,
                    'notes' => $l->notes,
                ),
                'labels' => $this->labelMap(),
                'url' => '/modules/ksf_FA_HRMBanks/pages/bank_ledger_form.php?action=edit&ledger_id=' . $l->id,
                'label' => $l->getDisplayLabel(),
            );
        }
        return $results;
    }
    public function listByEmployee(string $employeeId): array {
        $rows = $this->repo->findByEmployee($employeeId);
        $results = array();
        foreach ($rows as $row) {
            $l = HRMBankLedgerDTO::fromArray($row);
            $results[] = array(
                'id' => $l->id,
                'dto' => new RelatedRecordDto('hrm_bank_ledger', (string) $l->id, 'hrm_bank_ledger', (string) $l->id, null),
                'fields' => array(
                    'transaction_type' => $l->transaction_type,
                    'amount' => $l->amount,
                    'balance_after' => $l->balance_after,
                    'transaction_date' => $l->transaction_date,
                    'reference' => $l->reference,
                    'notes' => $l->notes,
                ),
                'labels' => $this->labelMap(),
                'url' => '/modules/ksf_FA_HRMBanks/pages/bank_ledger_form.php?action=edit&ledger_id=' . $l->id,
                'label' => $l->getDisplayLabel(),
            );
        }
        return $results;
    }
    public function save(array $data): int {
        return $this->repo->save($data);
    }
    public function update(int $id, array $data): void {
        $this->repo->update($id, $data);
    }
    public function delete(int $id): void {
        $this->repo->delete($id);
    }
    public function fieldMap(): array { return $this->labelMap(); }
    public function labelMap(): array {
        return array(
            'transaction_type' => _('Transaction Type'),
            'amount' => _('Amount'),
            'balance_after' => _('Balance After'),
            'transaction_date' => _('Transaction Date'),
            'reference' => _('Reference'),
            'notes' => _('Notes'),
        );
    }
}
