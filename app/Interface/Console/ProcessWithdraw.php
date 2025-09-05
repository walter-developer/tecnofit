<?php

declare(strict_types=1);

namespace App\Interface\Console;

use App\Application\AccountApplication;
use App\Domain\Contracts\AccountWithdrawContract;
use App\Domain\Entities\AccountWithdraw;
use Carbon\Carbon;
use Hyperf\Command\Command as HyperfCommand;
use Symfony\Component\Console\Input\InputArgument;
use Hyperf\Contract\StdoutLoggerInterface;

class ProcessWithdraw extends HyperfCommand
{

    private int $limit = 20;

    public function __construct(
        protected AccountApplication $accountApplication,
        protected AccountWithdrawContract $accountWithdrawRepository,
        protected StdoutLoggerInterface $logger
    ) {
        parent::__construct('command:process-withdraw');
    }

    public function configure()
    {
        $this->setDescription('Processra saques agendados.');
        $this->addArgument('limit', InputArgument::OPTIONAL, 'Número máximo de registros por lote', 20);
    }

    private function process(AccountWithdraw $accountWithdraw)
    {
        $this->accountApplication->processWithdrawal($accountWithdraw);
    }

    private function limit(): int
    {
        return intval((property_exists($this, 'input')  && $this->input)
            ?  $this->input->getArgument('limit') : $this->limit);
    }

    public function handle()
    {
        $now = Carbon::now()->format('Y-m-d Y-m-d H:i:s');
        $this->logger->info("Processando saques com data inferior a: $now");
        $limit = $this->limit();
        $this->accountWithdrawRepository
            ->chunkScheduledWithdrawals(fn(AccountWithdraw $accountWithdraw)
            => $this->process($accountWithdraw), $limit);
    }
}
