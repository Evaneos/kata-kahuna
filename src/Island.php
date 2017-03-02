<?php

namespace Kata;

class Island
{
    /** @var string */
    private $name;

    /**
     * @param Map    $map
     * @param string $name
     */
    public function __construct(Map $map, $name)
    {
        $this->name = $name;
        $this->map = $map;
    }

    /**
     * @return Link[]
     */
    public function getLinks()
    {
        return $this->map->getLinks($this);
    }

    /**
     * @return Link[]
     */
    public function getAvailableLinks()
    {
        return array_filter($this->getLinks(), function (Link $link) {
            return !$link->hasBridge();
        });
    }

    /**
     * @param string $player
     * @return Link[]
     */
    public function getLinksTakenByPlayer($player)
    {
        return array_filter($this->getLinks(), function (Link $link) use ($player) {
            return $link->ownsBridge($player);
        });
    }

    /**
     * @param string $player
     * @return bool
     */
    public function ownedBy($player)
    {
        return count($this->getLinksTakenByPlayer($player)) > count($this->getLinks()) / 2;
    }

    /**
     * @param Island $island
     * @return bool
     */
    public function isLinkedTo(Island $island)
    {
        return $this->getLinkTo($island) !== null;
    }

    /**
     * @param string $player
     * @param Island $island
     */
    public function takeLink($player, $island)
    {
         $this->getLinkTo($island)->buildBridge($player);

         if ($this->ownedBy($player)) {
             $this->destroyLinksNotOwnedBy($player);
         }
    }

    /**
     * @param Island $island
     * @return Link|mixed
     */
    private function getLinkTo(Island $island)
    {
        return $this->map->getLinkBetween($this, $island);
    }

    /**
     * @param string $player
     */
    private function destroyLinksNotOwnedBy($player)
    {
        foreach ($this->getLinksOwnedByOtherPlayer($player) as $link) {
            $link->destroyBridge();
        }
    }

    /**
     * @param string $player
     * @return Link[]
     */
    private function getLinksOwnedByOtherPlayer($player)
    {
        return array_filter($this->getLinks(), function (Link $link) use ($player) {
            return $link->hasBridge() && !$link->ownsBridge($player);
        });
    }
}
