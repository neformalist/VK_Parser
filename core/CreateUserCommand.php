<?php
/**
 * Created by PhpStorm.
 * User: alexander
 * Date: 23.07.17
 * Time: 22:14
 */

namespace Parser;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputOption;
use DataSource\Database;


class CreateUserCommand extends Command
{
    /**
     * @var int
     */
    private $count;

    /**
     * @var int
     */
    private $offset;

    protected function configure()
    {
        $this->setName('parse-user')
            ->setDescription('Creates a new user.')
            ->addOption('count',null,InputOption::VALUE_OPTIONAL)
            ->addOption('offset',null,InputOption::VALUE_OPTIONAL)
            ->setHelp('This command allows you to create a user...');
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->count = (int)$input->getOption('count') ?: 50;
        $this->offset = (int)$input->getOption('offset') ?: rand(10, 10000000);
        parent::initialize($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $progress = new ProgressBar($output, $this->count);
        $curl = new Curl();
        $parser = new Parser(Database::getInstance(), $curl);

        $output->writeln("<info>Is added $this->count users from $this->offset</info>");
        $progress->setFormat('verbose');
        $progress->setBarCharacter('<info>=</info>');
        $progress->start();
        $progress->setBarWidth(100);

        $increment = 1;
        while ($increment <= $this->count){
            if($parser->parseUser($this->offset)){
                $progress->advance();
                $increment++;
            }
            $this->offset++;
        }

        $output->writeln('');
        $output->writeln('<info>Done!!!</info>');
    }
}