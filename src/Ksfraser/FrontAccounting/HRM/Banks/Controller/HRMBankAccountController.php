<?php

declare(strict_types=1);

namespace Ksfraser\FrontAccounting\HRM\Banks\Controller;

use Ksfraser\FrontAccounting\Common\App\AbstractTabController;
use Ksfraser\FrontAccounting\HRM\Banks\Service\HRMBankAccountService;
use ksfraser\RelatedTo\DTO\RelatedQueryDto;
use ksfraser\RelatedTo\UI\DefaultRelatedRecordsRenderer;

class HRMBankAccountController extends AbstractTabController
{
    private $service;

    public function __construct($context = null, array $options = [])
    {
        parent::__construct($context, $options);
        $this->service = new HRMBankAccountService();
    }

    protected function getPkField(): string { return 'id'; }

    protected function getFieldMetadata(): array
    {
        return [
            'entity'      => 'hrm_bank_account',
            'table'       => '0_hrm_bank_accounts',
            'label'       => 'Bank Account',
            'labelPlural' => 'Bank Accounts',
            'hookPrefix'  => 'hrm_bank_account',
            'pk'          => 'id',
            'fields'      => [],
            'fk_ddls'   => [],
            'ddlHooks'  => [],
            'tableSettings' => [],
        ];
    }

    protected function listRows(int $page, int $perPage): array
    {
        $offset = ($page - 1) * $perPage;
        return array_slice($this->service->listAll(), $offset, $perPage);
    }

    protected function countRows(): int
    {
        return count($this->service->listAll());
    }

    protected function findRecord(string $pk): ?array { return null; }
    protected function createRecord(array $data): int { return 0; }
    protected function updateRecord(string $pk, array $data): void {}
    protected function deleteRecord(string $pk): void {}
    protected function fkOptions(): array { return []; }

    protected function renderSummaryTable(): void
    {
        if (!function_exists('start_table')) return;

        $page = max(1, $this->page());
        $perPage = $this->perPage;
        $rows = $this->listRows($page, $perPage);

        start_table(TABLESTYLE2, "width='95%'");
        $th = function (string $label): void {
            echo '<th>' . htmlspecialchars($this->localise($label), ENT_QUOTES) . '</th>';
        };
        echo '<tr>';
        $th(_('Employee'));
        $th(_('Bank Type'));
        $th(_('Bank Name'));
        $th(_('Current Balance'));
        $th(_('Available Balance'));
        $th(_('Accrued Balance'));
        $th(_('Used Balance'));
        $th(_('Actions'));
        echo '</tr>';

        if (empty($rows)) {
            echo '<tr><td colspan="8"><em>'
                . htmlspecialchars($this->localise('No bank accounts yet.'), ENT_QUOTES)
                . '</em></td></tr>';
        }

        foreach ($rows as $i => $row) {
            echo '<tr class="' . ($i % 2 ? 'evenrow' : 'oddrow') . '">';
            echo '<td>' . htmlspecialchars($row['fields']['employee_name'] ?? '', ENT_QUOTES, 'UTF-8') . '</td>';
            echo '<td>' . htmlspecialchars($row['fields']['bank_type'] ?? '', ENT_QUOTES, 'UTF-8') . '</td>';
            echo '<td>' . htmlspecialchars($row['fields']['bank_name'] ?? '', ENT_QUOTES, 'UTF-8') . '</td>';
            echo '<td>' . htmlspecialchars($row['fields']['current_balance'] ?? '', ENT_QUOTES, 'UTF-8') . '</td>';
            echo '<td>' . htmlspecialchars($row['fields']['available_balance'] ?? '', ENT_QUOTES, 'UTF-8') . '</td>';
            echo '<td>' . htmlspecialchars($row['fields']['accrued_balance'] ?? '', ENT_QUOTES, 'UTF-8') . '</td>';
            echo '<td>' . htmlspecialchars($row['fields']['used_balance'] ?? '', ENT_QUOTES, 'UTF-8') . '</td>';
            echo '<td><a href="' . htmlspecialchars($row['url'] ?? '#', ENT_QUOTES, 'UTF-8') . '" target="_blank">'
                . htmlspecialchars($this->localise('View'), ENT_QUOTES, 'UTF-8') . '</a></td>'
            . '</tr>';
        }

        end_table(0);
    }

    protected function renderNotes(): void
    {
        $lhsId = isset($_GET['id']) ? (string) $_GET['id'] : '';
        if ($lhsId === '' || !function_exists('hook_invoke')) return;

        $queryDto = new RelatedQueryDto('hrm_bank_account', $lhsId);
        $queryResult = $queryDto;
        hook_invoke('ksf_FA_RelatedTo', 'RELATED_TO', $queryResult, null);

        if (!is_array($queryResult) || !isset($queryResult['related']) || !is_array($queryResult['related'])) {
            return;
        }

        $renderer = new DefaultRelatedRecordsRenderer(array(
            'css_class' => 'related-records',
            'header_class' => 'related-header',
            'table_class' => 'related-table',
            'table_border' => true,
            'type_labels' => array('hrm_bank_account' => _('Bank Account')),
        ));
        echo $renderer->renderRelatedRecords('hrm_bank_account', $lhsId, $queryResult['related'], array(
            'css_class' => 'related-records',
            'header_class' => 'related-header',
            'table_class' => 'related-table',
            'table_border' => true,
        ));
    }
}
