<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AppLoadFixturesCommand extends Command
{
    protected static $defaultName = 'app:load-fixtures';

    /** @var \Fidry\AliceDataFixtures\Loader\PurgerLoader */
    protected $purgerLoader;

    /** @var string */
    protected $projectDirectory;

    public function __construct(\Fidry\AliceDataFixtures\Loader\PurgerLoader $purgerLoader, $projectDirectory)
    {
        $this->purgerLoader = $purgerLoader;
        $this->projectDirectory = $projectDirectory;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Load data fixtures')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $this->purgerLoader->load([$this->projectDirectory.'/src/DataFixtures/Person.yml']);

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
    }
}
