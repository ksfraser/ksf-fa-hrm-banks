<?php
declare(strict_types=1);
namespace Ksfraser\FrontAccounting\HRM\Banks\Tests\Unit;
use Ksfraser\FrontAccounting\HRM\Banks\DTO\HRMBankAccountDTO;
use PHPUnit\Framework\TestCase;
class HRMBankAccountDtoTest extends TestCase {
    public function testGetDisplayLabel(): void {
        $dto = new HRMBankAccountDTO(7, 'E123', 'Alice Smith', 'pto', 'PTO Bank', 'PTO', '40.00', '35.00', '50.00', '10.00', '2027-01-01', true, '');
        $this->assertSame('Alice Smith — PTO Bank — 40.00', $dto->getDisplayLabel());
    }
    public function testGetDisplayLabelMinimal(): void {
        $dto = new HRMBankAccountDTO(3, '', '', '', '', '', '', '', '', '', '', true, '');
        $this->assertSame('Bank Account #3', $dto->getDisplayLabel());
    }
    public function testToArrayShape(): void {
        $dto = new HRMBankAccountDTO(7, 'E123', 'Alice Smith', 'pto', 'PTO Bank', 'PTO', '40.00', '35.00', '50.00', '10.00', '2027-01-01', true, '');
        $array = $dto->toArray();
        $this->assertSame(7, $array['id']);
        $this->assertSame('E123', $array['employee_id']);
        $this->assertSame('Alice Smith', $array['employee_name']);
        $this->assertSame('pto', $array['bank_type']);
        $this->assertSame('40.00', $array['current_balance']);
    }
    public function testFromArrayReconstructs(): void {
        $dto = HRMBankAccountDTO::fromArray(array(
            'id' => 9, 'employee_id' => 'E456', 'employee_name' => 'Bob Jones',
            'bank_type' => 'vac', 'bank_type_name' => 'Vacation Bank',
            'bank_name' => 'Vacation', 'current_balance' => '20.00', 'available_balance' => '15.00',
            'accrued_balance' => '25.00', 'used_balance' => '5.00', 'expires_on' => '2027-12-31',
            'is_active' => true, 'created_at' => ''
        ));
        $this->assertSame(9, $dto->id);
        $this->assertSame('E456', $dto->employee_id);
        $this->assertSame('Bob Jones', $dto->employee_name);
        $this->assertSame('vac', $dto->bank_type);
        $this->assertSame('Vacation Bank', $dto->bank_type_name);
        $this->assertTrue($dto->is_active);
    }
}
