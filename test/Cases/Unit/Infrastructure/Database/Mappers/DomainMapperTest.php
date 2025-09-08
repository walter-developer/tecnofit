<?php

namespace HyperfTest\Cases\Unit\Infrastructure\Database\Mappers;

use App\Domain\Entities\Account;
use App\Domain\Entities\AccountWithdraw;
use App\Domain\Entities\AccountWithdrawPix;
use App\Infrastructure\Database\Mappers\DomainMapper;
use App\Infrastructure\Database\Models\Account as AccountDb;
use App\Infrastructure\Database\Models\AccountWithdraw as AccountWithdrawDb;
use App\Infrastructure\Database\Models\AccountWithdrawPix as AccountWithdrawPixDb;
use DateTime;
use PHPUnit\Framework\TestCase;

class DomainMapperTest extends TestCase
{
    private $mapper;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mapper = new class {

            use DomainMapper;

            public function publicToAccountDomain($db)
            {
                return $this->toAccountDomain($db);
            }

            public function publicToAccountWithdrawDomain($db)
            {
                return $this->toAccountWithdrawDomain($db);
            }

            public function publicToAccountWithdrawPixDomain($db)
            {
                return $this->toAccountWithdrawPixDomain($db);
            }
        };
    }

    public function testToAccountDomain()
    {
        $db = new AccountDb();
        $db->id = 'acc1';
        $db->name = 'Walter';
        $db->balance = 100.50;
        $db->created_at = new DateTime('2025-01-01 12:00:00');
        $db->updated_at = new DateTime('2025-01-02 12:00:00');
        $db->deleted_at = null;

        $domain = $this->mapper->publicToAccountDomain($db);

        $this->assertInstanceOf(Account::class, $domain);
        $this->assertSame('acc1', $domain->id());
        $this->assertSame('Walter', $domain->name());
        $this->assertSame(100.50, $domain->balance());
    }

    public function testToAccountWithdrawDomain()
    {
        $accountDb = new AccountDb();
        $accountDb->id = 'acc2';
        $accountDb->name = 'Maria';
        $accountDb->balance = 200;

        $db = new AccountWithdrawDb();
        $db->id = 'w1';
        $db->account = $accountDb;
        $db->method = 'PIX';
        $db->amount = 50.0;
        $db->scheduled = false;
        $db->done = false;
        $db->error = false;
        $db->error_reason = null;
        $db->scheduled_for = new DateTime('2025-01-03 15:00:00');
        $db->created_at = new DateTime('2025-01-01 12:00:00');
        $db->updated_at = new DateTime('2025-01-02 12:00:00');
        $db->deleted_at = null;

        $domain = $this->mapper->publicToAccountWithdrawDomain($db);

        $this->assertInstanceOf(AccountWithdraw::class, $domain);
        $this->assertSame('w1', $domain->id());
        $this->assertSame(50.0, $domain->amount());
        $this->assertSame('PIX', $domain->method()->value);
        $this->assertInstanceOf(Account::class, $domain->account());
    }

    public function testToAccountWithdrawPixDomain()
    {
        $accountDb = new AccountDb();
        $accountDb->id = 'acc3';
        $accountDb->name = 'João';
        $accountDb->balance = 300;

        $withdrawDb = new AccountWithdrawDb();
        $withdrawDb->id = 'w2';
        $withdrawDb->account = $accountDb;
        $withdrawDb->method = 'PIX';
        $withdrawDb->amount = 75.0;
        $withdrawDb->scheduled = true;
        $withdrawDb->done = false;
        $withdrawDb->error = false;
        $withdrawDb->error_reason = null;
        $withdrawDb->scheduled_for = new DateTime('2025-01-04 16:00:00');
        $withdrawDb->created_at = new DateTime('2025-01-01 12:00:00');
        $withdrawDb->updated_at = new DateTime('2025-01-02 12:00:00');
        $withdrawDb->deleted_at = null;

        $pixDb = new AccountWithdrawPixDb();
        $pixDb->id = 'pix1';
        $pixDb->type = 'CPF';
        $pixDb->key = '12345678900';
        $pixDb->withdraw = $withdrawDb;
        $pixDb->created_at = new DateTime('2025-01-01 12:00:00');
        $pixDb->updated_at = new DateTime('2025-01-02 12:00:00');
        $pixDb->deleted_at = null;

        $domain = $this->mapper->publicToAccountWithdrawPixDomain($pixDb);

        $this->assertInstanceOf(AccountWithdrawPix::class, $domain);
        $this->assertSame('pix1', $domain->id());
        $this->assertSame('CPF', $domain->type()->value);
        $this->assertSame('12345678900', $domain->key());
        $this->assertInstanceOf(AccountWithdraw::class, $domain->accountWithdraw());
    }
}
