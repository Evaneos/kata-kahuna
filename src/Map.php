<?php

namespace Kata;

class Map
{
    /** @var Link[] */
    private $links;

    /**
     */
    public function __construct()
    {
        $this->links = [];
    }

    /**
     * @param Link $link
     */
    public function addLink(Link $link)
    {
        $this->links[] = $link;
    }

    /**
     * @param Island $island
     * @return Link[]
     */
    public function getLinks(Island $island)
    {
        return array_filter($this->links, function (Link $link) use ($island) {
            return $link->goesTo($island);
        });
    }

    /**
     * @param Island $islandOne
     * @param Island $islandTwo
     * @return Link[]
     */
    public function getLinkBetween(Island $islandOne, Island $islandTwo)
    {
        return array_reduce(
            $this->links,
            function ($kept, Link $link) use ($islandOne, $islandTwo) {
                if ($link->goesTo($islandOne) && $link->goesTo($islandTwo)) {
                    return $link;
                }

                return $kept;
            },
            null);
    }
}
