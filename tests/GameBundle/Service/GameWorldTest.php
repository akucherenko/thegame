<?php

namespace GameBundle\Tests\Service;

// use GameBundle\Client\ClientInterface;
// use GameBundle\Exception\GameSessionAlreadyStartedException;
use GameBundle\Service\GameWorld;
// use GameBundle\Session\GameSession;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GameWorldTest extends \PHPUnit_Framework_TestCase
{
    private $world;

    protected function setUp()
    {
        $this->world = new GameWorld();
    }

    public function test_getting_first_step()
    {
        $step = $this->world->getFirstStep();

        $this->assertInstanceOf('GameBundle\Entity\Step', $step);
    }
}
