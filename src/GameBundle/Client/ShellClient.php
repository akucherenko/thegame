<?php

namespace GameBundle\Client;

use GameBundle\Entity\Option;
use GameBundle\Exception\ShutdownException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ShellClient implements ClientInterface
{
    /**
     * @var InputInterface
     */
    private $input;

    /**
     * @var OutputInterface
     */
    private $output;

    public function __construct(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
    }

    public function showDescription(string $description)
    {
        $this->output->writeln('');
        $this->output->writeln('>>> ' . $description);
        $this->output->writeln('');
    }

    public function notify(string $message)
    {
        $this->output->writeln('<fg=black;bg=yellow> ' . $message . ' </>');
        $this->output->writeln('');
    }

    /**
     * @param Option[] $options
     *
     * @return Option
     */
    public function askPlayer(array $options)
    {
        $counter = 0;

        foreach ($options as $option) {
            $counter ++;
            $this->output->writeln(sprintf(
                '%d. %s', $counter, $option->getText()
            ));
        }

        $this->output->writeln('');

        $choice = readline('$: ');

        if ($choice === false) {
            $this->output->writeln('');
            $this->shutdown();
        }

        $choice = (int) $choice;

        while (!($choice > 0 && $choice <= $counter)) {
            $this->output->writeln('<fg=red>Wrong input</>');
            $choice = (int) readline('$: ');
        }

        return $options[$choice - 1];
    }

    public function getResumeToken()
    {
        return $this->input->getOption('resume');
    }

    /**
     * Throws shutdown exception to save game progress
     *
     * @throws ShutdownException
     */
    private function shutdown()
    {
        throw new ShutdownException();
    }
}
