<?php

namespace GameBundle\Entity;

class Step
{
    const UNGUESS_HASH_SALT = "d%GiUqy1yiND@ye918eh";

    /**
     * @var string
     */
    protected $hash;

    /**
     * @var string
     */
    protected $description;

    protected $options = [];

    public function __construct($hash)
    {
        $this->hash = $hash;
    }

    public function getHash()
    {
        return $this->hash;
    }

    public function addOption(Option $option)
    {
        $this->options[] = $option;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Gets the value of description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets the value of description.
     *
     * @param string $description the description
     *
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    public function getResumeToken()
    {
        return md5(self::UNGUESS_HASH_SALT . $this->hash);
    }
}
