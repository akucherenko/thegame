<?php

namespace GameBundle\Entity;

class StartStep extends Step
{
    const START_STEP_HASH = 'start';

    public function __construct()
    {
        $this->hash = self::START_STEP_HASH;
    }
}
