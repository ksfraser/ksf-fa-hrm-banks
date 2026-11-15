<?php
declare(strict_types=1);
use Ksfraser\FrontAccounting\HRM\Banks\Service\HRMBankLedgerService;
$path_to_root = '../../..';
include_once($path_to_root . '/includes/session.inc');
add_access_extensions();
$autoload = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($autoload)) { display_error(_('HRM Banks module dependencies are not installed')); exit; }
require_once $autoload;
$service = new HRMBankLedgerService();
$action = isset($_GET['action']) ? (string) $_GET['action'] : 'list';
if ($action === 'list') {
    page(_('Bank Ledger'));
    $ledger = $service->listAll();
    echo '<p>' . _('Bank Ledger') . '</p>';
    echo '<table class="selection">';
    echo '<thead><tr>'
        . '<th>' . _('Bank') . '</th>'
        . '<th>' . _('Employee') . '</th>'
        . '<th>' . _('Transaction Type') . '</th>'
        . '<th>' . _('Amount') . '</th>'
        . '<th>' . _('Balance After') . '</th>'
        . '<th>' . _('Transaction Date') . '</th>'
        . '<th>' . _('Reference') . '</th>'
        . '<th>' . _('Notes') . '</th>'
        . '</tr></thead><tbody>';
    foreach ($ledger as $l) {
        echo '<tr>'
            . '<td>' . htmlspecialchars($l['fields']['bank_name'], ENT_QUOTES, 'UTF-8') . '</td>'
            . '<td>' . htmlspecialchars($l['fields']['employee_name'], ENT_QUOTES, 'UTF-8') . '</td>'
            . '<td>' . htmlspecialchars($l['fields']['transaction_type'], ENT_QUOTES, 'UTF-8') . '</td>'
            . '<td>' . htmlspecialchars($l['fields']['amount'], ENT_QUOTES, 'UTF-8') . '</td>'
            . '<td>' . htmlspecialchars($l['fields']['balance_after'], ENT_QUOTES, 'UTF-8') . '</td>'
            . '<td>' . htmlspecialchars($l['fields']['transaction_date'], ENT_QUOTES, 'UTF-8') . '</td>'
            . '<td>' . htmlspecialchars($l['fields']['reference'], ENT_QUOTES, 'UTF-8') . '</td>'
            . '<td>' . htmlspecialchars($l['fields']['notes'], ENT_QUOTES, 'UTF-8') . '</td>'
            . '</tr>';
    }
    echo '</tbody></table>';
    end_page();
    exit;
}
display_error(_('Unknown action'));
exit;
