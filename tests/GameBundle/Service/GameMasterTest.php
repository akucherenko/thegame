<?php

namespace GameBundle\Tests\Service;

use GameBundle\Client\ClientInterface;
use GameBundle\Exception\GameSessionAlreadyStartedException;
use GameBundle\Service\GameMaster;
use GameBundle\Session\GameSession;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GameMasterTest extends \PHPUnit_Framework_TestCase
{
    public function test_game_starts_successfully()
    {
        $clientInterface = $this->getMockBuilder(ClientInterface::class)->getMock();
        $gameMaster = new GameMaster();
        $session = $gameMaster->startGame($clientInterface);

        $this->assertInstanceOf(GameSession::class, $session);
    }

    public function test_repeated_start_game_fails()
    {
        $this->expectException(GameSessionAlreadyStartedException::class);

        $clientInterface = $this->getMockBuilder(ClientInterface::class)->getMock();
        $gameMaster = new GameMaster();

        $gameMaster->startGame($clientInterface);
        $gameMaster->startGame($clientInterface);
    }
}
