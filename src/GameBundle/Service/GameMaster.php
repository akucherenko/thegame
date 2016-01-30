<?php

namespace GameBundle\Service;

use GameBundle\Client\ClientInterface;
use GameBundle\Exception\GameSessionAlreadyStartedException;
use GameBundle\Exception\ShutdownException;
use GameBundle\Session\GameSession;

class GameMaster
{
    /**
     * @var GameSession
     */
    private $session;

    /**
     * @var GameWorld
     */
    private $world;

    /**
     * Constructor
     */
    public function __construct(GameWorld $world)
    {
        $this->world = $world;
    }

    /**
     * Creates new game session and assigns player to first step
     *
     * @param ClientInterface $client
     *
     * @return GameSession
     */
    public function startGame(ClientInterface $client)
    {
        if ($this->session) {
            throw new GameSessionAlreadyStartedException();
        }

        $this->session = new GameSession($client);

        if ($token = $this->session->resumeSession()) {
            $this->session->setCurrentStep($this->world->getResumeStep($token));
        } else {
            $this->session->setCurrentStep($this->world->getFirstStep());
        }

        return $this->session;
    }

    /**
     * Moves player from current step to next step in the world
     *
     * @return null
     */
    public function makeMove()
    {
        $currentPosition = $this->session->getCurrentStep();
        $options = $currentPosition->getOptions();
        $client = $this->session->getClient();

        $client->showDescription($currentPosition->getDescription());

        try {
            $playerAction = $client->askPlayer($options);
        } catch (ShutdownException $e) {
            $this->session->pause();
            return;
        }

        $success = $this->world->evaluatePlayerAction($currentPosition, $playerAction);

        if ($success) {
            $nextStep = $this->world->getStepByHash($playerAction->getReferenceHash());

            if ($playerAction->getSuccessMessage()) {
                $client->notify($playerAction->getSuccessMessage());
            }
        } else {
            $nextStep = $this->world->getFinalStep();

            $this->session->setGameIsLost(true);

            if ($playerAction->getFailureMessage()) {
                $client->notify($playerAction->getFailureMessage());
            }
        }

        $this->session->setCurrentStep($nextStep);
    }
}
