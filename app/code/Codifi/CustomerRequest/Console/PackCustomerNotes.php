<?php
/**
 * Codifi_CustomerRequest
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

namespace Codifi\CustomerRequest\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Codifi\CustomerRequest\Model\ExportCsv;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class PackCustomerNotes
 * @package Codifi\CustomerRequest\Console
 */
class PackCustomerNotes extends Command
{
    /**
     * Period
     */
    const PERIOD = 'period';

    /**
     * Export to csv file
     *
     * @var ExportCsv
     */
    private $exportCsv;

    /**
     * PackCustomerNotes constructor.
     *
     * @param ExportCsv $exportCsv
     */
    public function __construct(ExportCsv $exportCsv)
    {
        $this->exportCsv = $exportCsv;
        parent::__construct();
    }

    /**
     * Configures the current command
     */
    protected function configure()
    {
        $options = [
            new InputOption(
                self::PERIOD,
                null,
                InputOption::VALUE_REQUIRED,
                'period'
            )
        ];

        $this->setName('codifi:archive:notes');
        $this->setDescription('Archive customer notes that haven\'t updates for N (period) months');
        $this->setDefinition($options);

        parent::configure();
    }

    /**
     * Executes the current command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return $this
     * @throws NoSuchEntityException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($period = $input->getOption(self::PERIOD)) {
            $export = $this->exportCsv->export($period);
            if ($export === 'success'){
                $output->writeln("Customer notes that haven't updates " . $period . " months archived!");
            } else {
                $output->writeln($export);
            }
        } else {
            $output->writeln("You need to write period month. Like php bin/magento codifi:archive:notes --period [PERIOD]");
        }

        return $this;
    }
}