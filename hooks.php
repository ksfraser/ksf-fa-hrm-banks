<?php
declare(strict_types=1);
require_once __DIR__ . '/ComposerDependencies.php';
\Ksfraser\FrontAccounting\HRM\Banks\Utils\ComposerDependencies::ensure(__DIR__);
$autoload_path = dirname(__FILE__) . '/vendor/autoload.php';
if (file_exists($autoload_path)) { require_once $autoload_path; }
require_once __DIR__ . '/includes/common.inc';
require_once __DIR__ . '/includes/events.inc';
use ksfraser\RelatedTo\DTO\RelatedRecordDto;
define('SS_ksf_FA_HRMBanks', 180 << 8);
if (!defined('SA_HRMBANK_VIEW')) {
    define('SA_HRMBANK_VIEW', SS_ksf_FA_HRMBanks | 1);
    define('SA_HRMBANK_MANAGE', SS_ksf_FA_HRMBanks | 2);
    define('SA_HRMBANK_ADMIN', SS_ksf_FA_HRMBanks | 3);
}
class hooks_ksf_FA_HRMBanks extends hooks {
    const RESPONDERS = array('RELATED_TO_FIELDS', 'HRMBANK_RESOLVE', 'HRMBANK_FIELDS', 'HRMBANKLEDGER_RESOLVE', 'HRMBANKLEDGER_FIELDS');
    var $module_name = 'ksf_FA_HRMBanks';
    var $version = '1.0.0';
    function install_tabs($app) { }
    function install_options($app) { }
    function install_access() {
        $security_sections[SS_ksf_FA_HRMBanks] = _("HRM Banks");
        $security_areas['SA_HRMBANK_VIEW'] = array(SA_HRMBANK_VIEW, _("View HRM banks"));
        $security_areas['SA_HRMBANK_MANAGE'] = array(SA_HRMBANK_MANAGE, _("Manage HRM banks"));
        $security_areas['SA_HRMBANK_ADMIN'] = array(SA_HRMBANK_ADMIN, _("Administer HRM banks"));
        return array($security_areas, $security_sections);
    }
    function activate_extension($company, $check_only = true) {
        if ($check_only) { return true; }
        \Ksfraser\FrontAccounting\HRM\Banks\Utils\ComposerDependencies::ensure(__DIR__);
        $this->update_databases($company, array(
            '0_hrm_bank_accounts.sql' => array('hrm_bank_accounts'),
            '0_hrm_bank_ledger.sql' => array('hrm_bank_ledger'),
        ), false);
        return true;
    }
    private function ensure_composer_dependencies() {
        \Ksfraser\FrontAccounting\HRM\Banks\Utils\ComposerDependencies::ensure(__DIR__);
    }
    public function getModuleConstants(&$data, $opts = null) {
        $data['constants'] = array(
            'SS_ksf_FA_HRMBanks' => SS_ksf_FA_HRMBanks,
            'SA_HRMBANK_VIEW' => SA_HRMBANK_VIEW,
            'SA_HRMBANK_MANAGE' => SA_HRMBANK_MANAGE,
            'SA_HRMBANK_ADMIN' => SA_HRMBANK_ADMIN,
        );
        return true;
    }
    public function getModuleCapabilities(&$data, $opts = null) {
        $data['capabilities'] = self::RESPONDERS;
        return true;
    }
    public function hasCapability(&$data, $opts) {
        $capability = is_array($opts) && isset($opts['capability']) ? $opts['capability'] : null;
        if ($capability === null && isset($data['capability'])) { $capability = $data['capability']; }
        $data['has_capability'] = in_array($capability, self::RESPONDERS, true);
        return $data['has_capability'];
    }
    public function respondToCapabilityRequest(&$data, $opts = null) {
        $request = is_array($opts) && isset($opts['request']) ? $opts['request'] : (isset($data['request']) ? $data['request'] : 'capabilities');
        $data['request'] = $request;
        $data['module'] = $this->module_name;
        if (strpos($request, 'has:') === 0) { return $this->hasCapability($data, array('capability' => substr($request, 4))); }
        switch ($request) {
            case 'capabilities': return $this->getModuleCapabilities($data, $opts);
            case 'constants': return $this->getModuleConstants($data, $opts);
            default: $data['error'] = 'Unknown request type: ' . $request; return null;
        }
    }
    public function RELATED_TO_FIELDS(&$data, $opts = null) {
        if (!isset($data['module']) || !is_string($data['module'])) { $data['error'] = 'RELATED_TO_FIELDS requires data[module]'; return null; }
        if (!isset($data['type']) || !is_string($data['type'])) { $data['error'] = 'RELATED_TO_FIELDS requires data[type]'; return null; }
        if (!isset($data['fields']) || !is_array($data['fields'])) { $data['fields'] = array(); }
        if (!in_array($data['type'], ['hrm_bank_account', 'hrm_bank_ledger'])) { return null; }
        if ($data['type'] === 'hrm_bank_account') {
            $data['fields'] = array_merge($data['fields'], array(
                'bank_type' => _('Bank Type'),
                'bank_name' => _('Bank Name'),
                'current_balance' => _('Current Balance'),
                'available_balance' => _('Available Balance'),
                'accrued_balance' => _('Accrued Balance'),
                'used_balance' => _('Used Balance'),
                'expires_on' => _('Expires On'),
                'is_active' => _('Active'),
            ));
        } elseif ($data['type'] === 'hrm_bank_ledger') {
            $data['fields'] = array_merge($data['fields'], array(
                'transaction_type' => _('Transaction Type'),
                'amount' => _('Amount'),
                'balance_after' => _('Balance After'),
                'transaction_date' => _('Transaction Date'),
                'reference' => _('Reference'),
                'notes' => _('Notes'),
            ));
        }
        return null;
    }
    public function HRMBANK_RESOLVE(&$data, $opts = null) {
        if (!isset($data['bank_id']) || !is_numeric($data['bank_id'])) { $data['error'] = 'HRMBANK_RESOLVE requires data[bank_id]'; return null; }
        if (!isset($data['dto']) || !$data['dto'] instanceof RelatedRecordDto) { $data['error'] = 'HRMBANK_RESOLVE requires data[dto] as RelatedRecordDto'; return null; }
        $service = new \Ksfraser\FrontAccounting\HRM\Banks\Service\HRMBankAccountService();
        $data['result'] = $service->resolveById((int) $data['bank_id'], $data['dto']);
        return null;
    }
    public function HRMBANK_FIELDS(&$data, $opts = null) {
        $data['fields'] = array(
            'bank_type' => _('Bank Type'),
            'bank_name' => _('Bank Name'),
            'current_balance' => _('Current Balance'),
            'available_balance' => _('Available Balance'),
            'accrued_balance' => _('Accrued Balance'),
            'used_balance' => _('Used Balance'),
            'expires_on' => _('Expires On'),
            'is_active' => _('Active'),
        );
        return null;
    }
    public function HRMBANKLEDGER_RESOLVE(&$data, $opts = null) {
        if (!isset($data['ledger_id']) || !is_numeric($data['ledger_id'])) { $data['error'] = 'HRMBANKLEDGER_RESOLVE requires data[ledger_id]'; return null; }
        if (!isset($data['dto']) || !$data['dto'] instanceof RelatedRecordDto) { $data['error'] = 'HRMBANKLEDGER_RESOLVE requires data[dto] as RelatedRecordDto'; return null; }
        $service = new \Ksfraser\FrontAccounting\HRM\Banks\Service\HRMBankLedgerService();
        $data['result'] = $service->resolveById((int) $data['ledger_id'], $data['dto']);
        return null;
    }
    public function HRMBANKLEDGER_FIELDS(&$data, $opts = null) {
        $data['fields'] = array(
            'transaction_type' => _('Transaction Type'),
            'amount' => _('Amount'),
            'balance_after' => _('Balance After'),
            'transaction_date' => _('Transaction Date'),
            'reference' => _('Reference'),
            'notes' => _('Notes'),
        );
        return null;
    }
}

/**
 * crm_register_tabs - allows other modules to register their tabs with CRM.
 *
 * @param array &$data Modified by reference; 'tabs' array is appended to.
 */
function crm_register_tabs(&$data)
{
    if (!isset($data['tabs']) || !is_array($data['tabs'])) {
        $data['tabs'] = [];
    }
    // HRMBanks module registers its tab here
}
