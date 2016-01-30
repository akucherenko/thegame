<?php

namespace GameBundle\Entity\Factory;

use GameBundle\Entity\FinalStep;
use GameBundle\Entity\Option;
use GameBundle\Entity\StartStep;
use GameBundle\Entity\Step;

class StepFactory
{
    /**
     * Maps step data from a map file into an object
     *
     * @param array $stepData
     *
     * @return Step
     */
    public static function createStepFromArray(array $stepData)
    {
        /** @var Step $step */
        switch ($stepData['hash']) {
            case StartStep::START_STEP_HASH:
                $step = new StartStep();
                break;

            case FinalStep::FINAL_STEP_HASH:
                $step = new FinalStep();
                break;

            default:
                $step = new Step($stepData['hash']);
                break;
        }
        
        $step->setDescription($stepData['description']);

        foreach ($stepData['options'] as $option) {
            list($text, $probability, $referenceHash, $successMessage, $failureMessage) = $option;

            $option = new Option();
            $option->setText($text);
            $option->setProbability($probability);
            $option->setReferenceHash($referenceHash);
            $option->setSuccessMessage($successMessage);
            $option->setFailureMessage($failureMessage);

            $step->addOption($option);
        }

        return $step;
    }
}
