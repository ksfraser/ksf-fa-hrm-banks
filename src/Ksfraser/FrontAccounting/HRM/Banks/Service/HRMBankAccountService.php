<?php
declare(strict_types=1);
namespace Ksfraser\FrontAccounting\HRM\Banks\Service;
use Ksfraser\FrontAccounting\HRM\Banks\DTO\HRMBankAccountDTO;
use Ksfraser\FrontAccounting\HRM\Banks\Repository\HRMBankAccountRepository;
use ksfraser\RelatedTo\DTO\RelatedRecordDto;
final class HRMBankAccountService {
    private $repo;
    public function __construct(?HRMBankAccountRepository $repo = null) { $this->repo = $repo ?? new HRMBankAccountRepository(); }
    public function resolveById(int $id, ?RelatedRecordDto $dto = null): array {
        $row = $this->repo->findById($id);
        if ($row === null) {
            return array('dto' => new RelatedRecordDto('hrm_bank_account', '0', 'hrm_bank_account', '0', null), 'fields' => $this->fieldMap(), 'labels' => $this->labelMap(), 'url' => '#', 'label' => _('Bank Account #') . (string) $id);
        }
        $b = HRMBankAccountDTO::fromArray($row);
        $dto = $dto ?? new RelatedRecordDto('hrm_bank_account', (string) $b->id, 'hrm_bank_account', (string) $b->id, null);
        return array(
            'dto' => $dto,
            'fields' => array(
                'bank_type' => $b->bank_type,
                'bank_name' => $b->bank_name,
                'current_balance' => $b->current_balance,
                'available_balance' => $b->available_balance,
                'accrued_balance' => $b->accrued_balance,
                'used_balance' => $b->used_balance,
                'expires_on' => $b->expires_on,
                'is_active' => $b->is_active ? '1' : '0',
            ),
            'labels' => $this->labelMap(),
            'url' => '/modules/ksf_FA_HRMBanks/pages/bank_account_form.php?action=edit&bank_id=' . $b->id,
            'label' => $b->getDisplayLabel(),
        );
    }
    public function listAll(): array {
        $rows = $this->repo->findAll();
        $results = array();
        foreach ($rows as $row) {
            $b = HRMBankAccountDTO::fromArray($row);
            $results[] = array(
                'id' => $b->id,
                'dto' => new RelatedRecordDto('hrm_bank_account', (string) $b->id, 'hrm_bank_account', (string) $b->id, null),
                'fields' => array(
                    'bank_type' => $b->bank_type,
                    'bank_name' => $b->bank_name,
                    'current_balance' => $b->current_balance,
                    'available_balance' => $b->available_balance,
                    'accrued_balance' => $b->accrued_balance,
                    'used_balance' => $b->used_balance,
                    'expires_on' => $b->expires_on,
                    'is_active' => $b->is_active ? '1' : '0',
                ),
                'labels' => $this->labelMap(),
                'url' => '/modules/ksf_FA_HRMBanks/pages/bank_account_form.php?action=edit&bank_id=' . $b->id,
                'label' => $b->getDisplayLabel(),
            );
        }
        return $results;
    }
    public function listByEmployee(string $employeeId): array {
        $rows = $this->repo->findByEmployee($employeeId);
        $results = array();
        foreach ($rows as $row) {
            $b = HRMBankAccountDTO::fromArray($row);
            $results[] = array(
                'id' => $b->id,
                'dto' => new RelatedRecordDto('hrm_bank_account', (string) $b->id, 'hrm_bank_account', (string) $b->id, null),
                'fields' => array(
                    'bank_type' => $b->bank_type,
                    'bank_name' => $b->bank_name,
                    'current_balance' => $b->current_balance,
                    'available_balance' => $b->available_balance,
                    'accrued_balance' => $b->accrued_balance,
                    'used_balance' => $b->used_balance,
                    'expires_on' => $b->expires_on,
                    'is_active' => $b->is_active ? '1' : '0',
                ),
                'labels' => $this->labelMap(),
                'url' => '/modules/ksf_FA_HRMBanks/pages/bank_account_form.php?action=edit&bank_id=' . $b->id,
                'label' => $b->getDisplayLabel(),
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
            'bank_type' => _('Bank Type'),
            'bank_name' => _('Bank Name'),
            'current_balance' => _('Current Balance'),
            'available_balance' => _('Available Balance'),
            'accrued_balance' => _('Accrued Balance'),
            'used_balance' => _('Used Balance'),
            'expires_on' => _('Expires On'),
            'is_active' => _('Active'),
        );
    }
}
