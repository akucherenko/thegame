<?php

namespace GameBundle\Entity;

class Option
{
    /**
     * @var string
     */
    protected $text;

    /**
     * @var float
     */
    protected $probability;

    /**
     * @var string
     */
    protected $referenceHash;

    /**
     * @var string
     */
    protected $successMessage;

    /**
     * @var string
     */
    protected $failureMessage;

    /**
     * Gets the value of text.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Sets the value of text.
     *
     * @param string $text the text
     *
     * @return self
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Gets the value of probability.
     *
     * @return float
     */
    public function getProbability()
    {
        return $this->probability;
    }

    /**
     * Sets the value of probability.
     *
     * @param float $probability the probability
     *
     * @return self
     */
    public function setProbability($probability)
    {
        $this->probability = $probability;

        return $this;
    }

    /**
     * Gets the value of referenceHash.
     *
     * @return string
     */
    public function getReferenceHash()
    {
        return $this->referenceHash;
    }

    /**
     * Sets the value of referenceHash.
     *
     * @param string $referenceHash the reference hash
     *
     * @return self
     */
    public function setReferenceHash($referenceHash)
    {
        $this->referenceHash = $referenceHash;

        return $this;
    }

    /**
     * Gets the value of successMessage.
     *
     * @return string
     */
    public function getSuccessMessage()
    {
        return $this->successMessage;
    }

    /**
     * Sets the value of successMessage.
     *
     * @param string $successMessage the success message
     *
     * @return self
     */
    public function setSuccessMessage($successMessage)
    {
        $this->successMessage = $successMessage;

        return $this;
    }

    /**
     * Gets the value of failureMessage.
     *
     * @return string
     */
    public function getFailureMessage()
    {
        return $this->failureMessage;
    }

    /**
     * Sets the value of failureMessage.
     *
     * @param string $failureMessage the failure message
     *
     * @return self
     */
    public function setFailureMessage($failureMessage)
    {
        $this->failureMessage = $failureMessage;

        return $this;
    }
}
