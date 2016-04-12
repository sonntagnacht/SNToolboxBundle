<?php
/**
 * SNToolboxBundle
 * Created by PhpStorm.
 * File: ImportDBCommand.php
 * User: Conrad
 * Date: 03.07.2015
 * Time: 11:04
 */
namespace SN\ToolboxBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use SN\ToolboxBundle\Helper\CommandHelper;

class ImportDBCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('sn:util:import:db')
            ->setDescription('Imports a (gziped) dump to database')
            ->addArgument('dump', InputArgument::REQUIRED, 'The path to the (gziped) dump file');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $filename   = realpath($input->getArgument('dump'));
        $filesystem = new Filesystem();
        if (!$filesystem->exists($filename)) {
            throw new FileNotFoundException(sprintf('File [%s] was not found', $filename));
        }

        $output->writeln(sprintf('Importing file <info>%s</info>', $filename));
        CommandHelper::countdown($output, 5);

        $fileInfo = new \SplFileInfo($filename);
        if ($fileInfo->getExtension() == 'gz') {
            CommandHelper::executeCommand(
                sprintf('gunzip < %s | mysql -h%s -u%s -p%s %s',
                    $filename,
                    $this->getContainer()->getParameter('database_host'),
                    $this->getContainer()->getParameter('database_user'),
                    $this->getContainer()->getParameter('database_password'),
                    $this->getContainer()->getParameter('database_name')
                ),
                $output
            );
        } else {
            CommandHelper::executeCommand(
                sprintf('mysql -h%s -u%s -p%s %s',
                    $filename,
                    $this->getContainer()->getParameter('database_host'),
                    $this->getContainer()->getParameter('database_user'),
                    $this->getContainer()->getParameter('database_password'),
                    $this->getContainer()->getParameter('database_name')
                ),
                $output
            );
        }

    }

}