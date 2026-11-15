<?php
declare(strict_types=1);
use Ksfraser\FrontAccounting\HRM\Banks\Service\HRMBankAccountService;
$path_to_root = '../../..';
include_once($path_to_root . '/includes/session.inc');
add_access_extensions();
$autoload = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($autoload)) { display_error(_('HRM Banks module dependencies are not installed')); exit; }
require_once $autoload;
$service = new HRMBankAccountService();
$action = isset($_GET['action']) ? (string) $_GET['action'] : 'list';
if ($action === 'list') {
    page(_('HRM Banks'));
    $banks = $service->listAll();
    echo '<p>' . _('HRM Banks') . '</p>';
    echo '<table class="selection">';
    echo '<thead><tr>'
        . '<th>' . _('Employee') . '</th>'
        . '<th>' . _('Bank Type') . '</th>'
        . '<th>' . _('Bank Name') . '</th>'
        . '<th>' . _('Current Balance') . '</th>'
        . '<th>' . _('Available Balance') . '</th>'
        . '<th>' . _('Accrued Balance') . '</th>'
        . '<th>' . _('Used Balance') . '</th>'
        . '<th>' . _('Expires On') . '</th>'
        . '<th>' . _('Active') . '</th>'
        . '<th>' . _('Actions') . '</th>'
        . '</tr></thead><tbody>';
    foreach ($banks as $b) {
        echo '<tr>'
            . '<td>' . htmlspecialchars($b['fields']['employee_name'], ENT_QUOTES, 'UTF-8') . '</td>'
            . '<td>' . htmlspecialchars($b['fields']['bank_type'], ENT_QUOTES, 'UTF-8') . '</td>'
            . '<td>' . htmlspecialchars($b['fields']['bank_name'], ENT_QUOTES, 'UTF-8') . '</td>'
            . '<td>' . htmlspecialchars($b['fields']['current_balance'], ENT_QUOTES, 'UTF-8') . '</td>'
            . '<td>' . htmlspecialchars($b['fields']['available_balance'], ENT_QUOTES, 'UTF-8') . '</td>'
            . '<td>' . htmlspecialchars($b['fields']['accrued_balance'], ENT_QUOTES, 'UTF-8') . '</td>'
            . '<td>' . htmlspecialchars($b['fields']['used_balance'], ENT_QUOTES, 'UTF-8') . '</td>'
            . '<td>' . htmlspecialchars($b['fields']['expires_on'], ENT_QUOTES, 'UTF-8') . '</td>'
            . '<td>' . ($b['fields']['is_active'] === '1' ? _('Yes') : _('No')) . '</td>'
            . '<td><a href="' . htmlspecialchars($b['url'], ENT_QUOTES, 'UTF-8') . '" target="_blank">' . _('View') . '</a></td>'
            . '</tr>';
    }
    echo '</tbody></table>';
    end_page();
    exit;
}
display_error(_('Unknown action'));
exit;
