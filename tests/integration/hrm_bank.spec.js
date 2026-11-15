import { test, expect } from '@playwright/test';

test.describe('ksf_FA_HRMBanks HRM Bank Module', () => {
  test('should load bank account list page', async ({ page }) => {
    await page.goto('/modules/ksf_FA_HRMBanks/pages/bank_accounts.php');
    await expect(page.locator('h1, h2')).toContainText(/Bank/i);
  });
});
