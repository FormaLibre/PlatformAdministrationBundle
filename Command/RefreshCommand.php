<?php

/*
 * This file is part of the Claroline Connect package.
 *
 * (c) Claroline Consortium <consortium@claroline.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FormaLibre\PlatformAdministrationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Claroline\CoreBundle\Library\Workspace\Configuration;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Psr\Log\LogLevel;

class RefreshCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('formalibre:platform_administration:refresh')->setDescription('Refresh the platform list');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $consoleLogger = new ConsoleLogger($output);
        $manager = $this->getContainer()->get('formalibre.manager.platform_manager');
        $verbosityLevelMap = array(
            LogLevel::NOTICE => OutputInterface::VERBOSITY_NORMAL,
            LogLevel::INFO   => OutputInterface::VERBOSITY_NORMAL,
            LogLevel::DEBUG  => OutputInterface::VERBOSITY_NORMAL
        );
        $manager->setLogger($consoleLogger, $verbosityLevelMap);
        $manager->refreshAll();
    }
}
