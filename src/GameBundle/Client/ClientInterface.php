<?php

namespace GameBundle\Client;

interface ClientInterface
{
    public function showDescription(string $description);

    public function notify(string $message);

    public function askPlayer(array $options);
}
