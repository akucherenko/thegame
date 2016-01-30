<?php

namespace GameBundle\Entity;

class FinalStep extends Step
{
    const FINAL_STEP_HASH = 'finale';

    public function __construct()
    {
        $this->hash = self::FINAL_STEP_HASH;
    }
}
