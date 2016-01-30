<?php

namespace GameBundle\Service;

use GameBundle\Entity\FinalStep;
use GameBundle\Entity\Option;
use GameBundle\Entity\StartStep;
use GameBundle\Entity\Step;
use GameBundle\Entity\Factory\StepFactory;
use GameBundle\Exception\InvalidPlayerActionException;
use GameBundle\Exception\GameMapFileNotFoundException;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class GameWorld
{
    const MAP_FILENAME = './data/map.yml';

    private $stepsRegistry = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->loadMap(self::MAP_FILENAME);
    }

    public function evaluatePlayerAction(Step $step, Option $option)
    {
        $options = $step->getOptions();

        if (array_search($option, $options) === false) {
            throw new InvalidPlayerActionException();
        }

        $playerChances = rand(1, 10) / 10;

        return $playerChances >= (1 - $option->getProbability());
    }

    public function getFirstStep()
    {
        return $this->stepsRegistry[StartStep::START_STEP_HASH];
    }

    public function getFinalStep()
    {
        return $this->stepsRegistry[FinalStep::FINAL_STEP_HASH];
    }

    public function getStepByHash(string $hash)
    {
        return $this->stepsRegistry[$hash];
    }

    /**
     * Returns a step by companing resume token
     *
     * @param string $token
     *
     * @return Step
     */
    public function getResumeStep(string $token)
    {
        foreach ($this->stepsRegistry as $hash => $step)
        {
            if ($step->getResumeToken() == $token) {
                return $step;
            }
        }

        throw new UnknownResumeTokenException();
    }

    protected function loadMap(string $filename)
    {
        if (!is_file($filename)) {
            throw new GameMapFileNotFoundException();
        }

        try {
            $mapContents = Yaml::parse(file_get_contents($filename));
        } catch (ParseException $e) {
            throw new GameMapFileMarkupCorrutionException();
        }

        if ($this->validateMap($mapContents)) {
            $this->buildMap($mapContents);
        } else {
            throw new GameMapCorruptionException();
        }
    }

    /**
     * Checks consistency of links.
     *
     * @todo validate `options` and `description` nodes
     *
     * @param string $mapContents
     *
     * @return bool Roughly checks whether map steps are consistent o not
     */
    protected function validateMap($mapContents)
    {
        $haveStartStep = false;
        $haveFinalStep = false;
        $refs = [];
        $steps = [];

        foreach ($mapContents['steps'] as $stepData) {
            if ($stepData['hash'] == 'start') {
                $haveStartStep = true;
            }
            if ($stepData['hash'] == FinalStep::FINAL_STEP_HASH) {
                $haveFinalStep = true;
            }
            $steps[] = $stepData['hash'];

            foreach ($stepData['options'] as $option) {
                list(,,$hash) = $option;
                $refs[] = $hash;
            }
        }

        $refs = array_filter($refs);
        $diff = array_diff($refs, $steps, [FinalStep::FINAL_STEP_HASH]);

        return $haveStartStep && $haveFinalStep && empty($diff);
    }

    protected function buildMap($mapContents)
    {
        foreach ($mapContents['steps'] as $stepData) {
            $this->stepsRegistry[$stepData['hash']] = StepFactory::createStepFromArray($stepData);
        }
    }
}
