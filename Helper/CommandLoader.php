<?php
/**
 * SNBundles
 * Created by PhpStorm.
 * File: CommandLoader.php
 * User: thomas
 * Date: 30.05.17
 * Time: 10:41
 */

namespace SN\ToolboxBundle\Helper;


use Symfony\Component\Console\Output\OutputInterface;

class CommandLoader
{
    const LOADER_SPINNER = 0x01;
    const LOADER_POINTS  = 0x02;

    /**
     * @var OutputInterface
     */
    protected $output;

    protected $message = "";

    protected $type = self::LOADER_SPINNER;

    protected $length;

    protected $loader = array(
        self::LOADER_SPINNER => array(
            "|",
            "/",
            "-",
            "\\"
        ),
        self::LOADER_POINTS  => array(
            "<options=bold>.</>..",
            ".<options=bold>.</>.",
            "..<options=bold>.</>"
        )
    );

    protected $pid = 0;

    protected $counter = 0;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    public function setType($type)
    {
        if (isset($this->loader[$type])) {
            $this->type = $type;
            $this->reload();
        }

        return $this;
    }

    public function setMessage($message)
    {
        $this->message = $message;

        if (false == $this->output->isVerbose() ||
            false === function_exists("pcntl_forc")
        ) {
            $this->output->writeln($this->message);

            return $this;
        }

        $this->reload();

        return $this;
    }

    protected function reload()
    {
        if ($this->pid > 0) {
            posix_kill($this->pid, SIGSTOP);
            $this->output->write("\x0D");
            $this->output->write("\x1B[2K");
            $this->run();
        }
    }

    public function run()
    {
        if (false == $this->output->isVerbose() ||
            false === function_exists("pcntl_forc")) {
            return;
        }

        $this->pid = pcntl_fork();
        if (0 === $this->pid) {
            $this->printLine();
        }
    }

    public function stop($message)
    {
        if ($this->pid > 0) {
            posix_kill($this->pid, SIGSTOP);
            $this->output->write("\x0D");
            $this->output->write("\x1B[2K");
        }

        $this->output->writeln($message);
    }

    protected function printLine()
    {
        $counter = 0;
        $loader  = $this->loader[$this->type];

        while (true) {
            $counter = ($counter + 1) % count($loader);
            $line    = sprintf("%s %s", $loader[$counter], $this->message);
            $this->output->write($line);
            usleep(200000);
            $this->output->write("\x0D");
            $this->output->write("\x1B[2K");
        }
    }

    public function __destruct()
    {
        if ($this->pid) {
            posix_kill($this->pid, SIGSTOP);
        }
    }
}