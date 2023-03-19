<?php

namespace App\Command;

use App\Service\ImportCurrenciesService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:import-currencies',
    description: 'import Currencies',
)]
class UpdateCurrenciesCommand extends Command
{
    public function __construct(
      private readonly ImportCurrenciesService $currenciesService
    ) {
        parent::__construct();
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->currenciesService->importCurrencies();
        return Command::SUCCESS;
    }
}
