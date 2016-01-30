<?php

namespace GameBundle\Session;

use GameBundle\Client\ClientInterface;
use GameBundle\Entity\Step;
use GameBundle\Entity\FinalStep;

class GameSession
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var Step
     */
    private $step;

    /**
     * @var bool
     */
    private $paused = false;

    /**
     * @var bool
     */
    private $gameIsLost = false;

    /**
     * Constructor
     *
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @return ClientInterface
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return boolean
     */
    public function isInProgress()
    {
        return !$this->paused && $this->getCurrentStep()->getHash() !== FinalStep::FINAL_STEP_HASH;
    }

    /**
     * @return boolean
     */
    public function isWon()
    {
        $result = false;

        if ($this->getCurrentStep()->getHash() === FinalStep::FINAL_STEP_HASH) {
            $result = !$this->gameIsLost;
        }

        return !$this->paused && $result;
    }

    /**
     * @return boolean
     */
    public function isPaused()
    {
        return $this->paused;
    }

    public function setGameIsLost(bool $gameIsLost)
    {
        $this->gameIsLost = $gameIsLost;
    }

    /**
     * @param Step $step
     *
     * @return self
     */
    public function setCurrentStep(Step $step)
    {
        $this->step = $step;

        return $this;
    }

    /**
     * @return Step
     */
    public function getCurrentStep()
    {
        return $this->step;
    }

    /**
     * Pauses session and notifies client
     */
    public function pause()
    {
        $this->paused = true;
        $this->getClient()->notify(
            sprintf(
                'Thanks for playing. To resume current game use token "%s"',
                $this->getCurrentStep()->getResumeToken()
            )
        );
    }

    /**
     * @return string
     */
    public function resumeSession()
    {
        return $this->getClient()->getResumeToken();
    }
}
