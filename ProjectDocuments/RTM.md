# RTM — ksf_FA_HRMBanks

| BR ID | Requirement | Implementing class/method | Test file | Status |
|-------|-------------|---------------------------|-----------|--------|
| BR-HB-001 | Bank account management | HRMBankAccountService::resolveById, listAll; HRMBankAccountRepository::findAll, findById, save, update, delete | HRMBankAccountDtoTest | Implemented |
| BR-HB-002 | Bank ledger | HRMBankLedgerService::resolveById, listAll; HRMBankLedgerRepository::findAll, findById, save, update, delete | HRMBankLedgerDtoTest | Implemented |
| BR-HB-003 | Expiration rules | expires_on field on bank accounts | — | Implemented |
| BR-HB-004 | Resolved DTOs for RelatedTo | HRMBankAccountService, HRMBankLedgerService (toArray, fieldMap, labelMap) | HRMBankAccountDtoTest, HRMBankLedgerDtoTest | Implemented |

