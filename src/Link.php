<?php

namespace Kata;

use Webmozart\Assert\Assert;

class Link
{
    /** @var string */
    private $owner;

    /** @var Island */
    private $islandFrom;

    /** @var Island */
    private $islandTo;

    /**
     * @param Island $islandFrom
     * @param Island $islandTo
     * @internal param Island $island
     */
    public function __construct(Island $islandFrom, Island $islandTo)
    {
        $this->islandFrom = $islandFrom;
        $this->islandTo = $islandTo;
    }

    /**
     * @param string $owner
     */
    public function buildBridge($owner)
    {
        if ($this->hasBridge()) {
            throw new BridgeAlreadyTakenException();
        }

        $this->owner = $owner;
    }

    public function destroyBridge()
    {
        $this->owner = null;
    }

    /**
     * @return bool
     */
    public function hasBridge()
    {
        return $this->owner !== null;
    }

    /**
     * @param string $player
     * @return bool
     */
    public function ownsBridge($player)
    {
        return $this->owner === $player;
    }

    /**
     * @param Island $island
     * @return bool
     */
    public function goesTo(Island $island)
    {
        return $this->islandFrom === $island || $this->islandTo === $island;
    }
}
