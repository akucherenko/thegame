<?php

namespace GameBundle\Command;

use GameBundle\Client\ClientInterface;
use GameBundle\Client\ShellClient;
use GameBundle\Service\GameMaster;
use GameBundle\Session\GameSession;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GameStartCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('game:start')
            ->setDescription('Start a new game')
            ->setHelp('The Game (𝜷)' . PHP_EOL . 'Use numeric options to move through the world.' . PHP_EOL . 'To pause use ^D.')
            ->addOption('resume', 'r', InputOption::VALUE_OPTIONAL, 'String token to resume unfinished game')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('To pause use ^D.');

        /** @var GameMaster */
        $gm = $this->getContainer()->get('the_game.game_master');
        /** @var ClientInterface */
        $clientInterface = new ShellClient($input, $output);
        /** @var GameSession */
        $gameSession = $gm->startGame($clientInterface);

        // $gameSession->begin();

        while ($gameSession->isInProgress()) {
            $gm->makeMove();
        }

        if (!$gameSession->isPaused()) {
            if ($gameSession->isWon()) {
                $output->writeln('<bg=green;fg=black> Congratulations! You won! </>');
            } else {
                $output->writeln('<bg=blue;fg=white> Game over! Try again... </>');
            }
        }
    }
}
