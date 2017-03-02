<?php

namespace Kata\Test;

use Kata\Island;
use Kata\Link;
use Kata\Map;

class KahunaTest extends \PHPUnit_Framework_TestCase
{
    /** @var string */
    private $whitePlayer;

    /** @var string */
    private $blackPlayer;

    /** @var Island */
    private $jojoIsland;

    /** @var Island */
    private $golaIsland;

    /** @var Island */
    private $faaaIsland;

    /** @var Island */
    private $kahuIsland;

    /** @var Island */
    private $iffiIsland;

    /** @var Island */
    private $elaiIsland;

    /** @var Map */
    private $map;

    /**
     * Init the mocks
     */
    public function setUp()
    {
        $this->map = new Map();
        $this->givenWhitePlayer();
        $this->givenBlackPlayer();
        $this->jojoIsland = $this->givenAnIsland('jojo');
        $this->golaIsland = $this->givenAnIsland('gola');
        $this->faaaIsland = $this->givenAnIsland('faaa');
        $this->kahuIsland = $this->givenAnIsland('kahu');
        $this->iffiIsland = $this->givenAnIsland('iffi');
        $this->elaiIsland = $this->givenAnIsland('elai');
    }

    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * @test
     */
    public function it_should_says_if_its_linked_to_a_given_island()
    {
        $this->givenJojoIslandIsLinkedTo($this->golaIsland);

        $this->assertTrue($this->jojoIsland->isLinkedTo($this->golaIsland));
    }

    /**
     * @test
     */
    public function it_should_says_a_given_island_is_linked_to_jojo()
    {
        $this->givenJojoIslandIsLinkedTo($this->golaIsland);

        $this->assertTrue($this->golaIsland->isLinkedTo($this->jojoIsland));
    }

    /**
     * @test
     */
    public function it_should_give_all_links()
    {
        $this->givenJojoIslandIsLinkedTo($this->golaIsland);
        $this->givenJojoIslandIsLinkedTo($this->faaaIsland);
        $this->givenJojoIslandIsLinkedTo($this->kahuIsland);

        $availableLinks = $this->jojoIsland->getLinks();

        $this->assertCount(3, $availableLinks);
    }

    /**
     * @test
     */
    public function it_should_give_available_links()
    {
        $this->givenJojoIslandIsLinkedTo($this->golaIsland);
        $this->givenJojoIslandIsLinkedTo($this->faaaIsland);
        $this->givenJojoIslandIsLinkedTo($this->kahuIsland);

        $this->givenLinkIsTakenBy($this->whitePlayer, $this->golaIsland);
        $this->givenLinkIsTakenBy($this->whitePlayer, $this->faaaIsland);

        $availableLinks = $this->jojoIsland->getAvailableLinks();

        $this->assertCount(1, $availableLinks);
    }

    /**
     * @test
     */
    public function it_should_give_links_taken_by_given_player()
    {
        $this->givenJojoIslandIsLinkedTo($this->golaIsland);
        $this->givenJojoIslandIsLinkedTo($this->faaaIsland);
        $this->givenJojoIslandIsLinkedTo($this->kahuIsland);

        $this->givenLinkIsTakenBy($this->whitePlayer, $this->golaIsland);
        $this->givenLinkIsTakenBy($this->whitePlayer, $this->faaaIsland);

        $whiteLinks = $this->jojoIsland->getLinksTakenByPlayer($this->whitePlayer);

        $this->assertCount(2, $whiteLinks);
    }

    /**
     * @test
     */
    public function it_should_says_when_the_island_is_owned_by_a_given_player()
    {
        $this->givenJojoIslandIsLinkedTo($this->golaIsland);
        $this->givenJojoIslandIsLinkedTo($this->faaaIsland);
        $this->givenJojoIslandIsLinkedTo($this->kahuIsland);

        $this->givenLinkIsTakenBy($this->blackPlayer, $this->golaIsland);
        $this->givenLinkIsTakenBy($this->blackPlayer, $this->faaaIsland);

        $this->assertTrue($this->jojoIsland->ownedBy($this->blackPlayer));
    }

    /**
     * @test
     * @expectedException \Kata\BridgeAlreadyTakenException
     */
    public function it_should_not_be_able_for_a_player_to_take_a_bridge_already_taken()
    {
        $this->givenJojoIslandIsLinkedTo($this->golaIsland);
        $this->givenLinkIsTakenBy($this->blackPlayer, $this->golaIsland);
        $this->givenLinkIsTakenBy($this->whitePlayer, $this->golaIsland);
    }

    /**
     * @test
     */
    public function it_should_destroy_adversary_link_ownership_when_an_island_is_owned()
    {
        $this->givenJojoIslandIsLinkedTo($this->golaIsland);
        $this->givenJojoIslandIsLinkedTo($this->faaaIsland);
        $this->givenJojoIslandIsLinkedTo($this->kahuIsland);

        $this->givenLinkIsTakenBy($this->whitePlayer, $this->kahuIsland);
        $this->givenLinkIsTakenBy($this->blackPlayer, $this->golaIsland);
        $this->givenLinkIsTakenBy($this->blackPlayer, $this->faaaIsland);

        $this->assertCount(0, $this->jojoIsland->getLinksTakenByPlayer($this->whitePlayer));
    }

    /**
     * @test
     */
    public function it_should_not_destroy_adversary_link_ownership_when_adversary_bridges_have_been_built_after_ownership()
    {
        $this->givenJojoIslandIsLinkedTo($this->golaIsland);
        $this->givenJojoIslandIsLinkedTo($this->faaaIsland);
        $this->givenJojoIslandIsLinkedTo($this->kahuIsland);
        $this->givenJojoIslandIsLinkedTo($this->iffiIsland);
        $this->givenJojoIslandIsLinkedTo($this->elaiIsland);

        $this->givenLinkIsTakenBy($this->blackPlayer, $this->kahuIsland);
        $this->givenLinkIsTakenBy($this->blackPlayer, $this->golaIsland);
        $this->givenLinkIsTakenBy($this->blackPlayer, $this->faaaIsland);
        $this->givenLinkIsTakenBy($this->whitePlayer, $this->iffiIsland);
        $this->givenLinkIsTakenBy($this->whitePlayer, $this->elaiIsland);

        $this->assertCount(2, $this->jojoIsland->getLinksTakenByPlayer($this->whitePlayer));
    }

    private function givenWhitePlayer()
    {
        $this->whitePlayer = 'white';
    }

    private function givenBlackPlayer()
    {
        $this->blackPlayer = 'black';
    }

    /**
     * @param string $name
     * @return Island
     */
    private function givenAnIsland($name)
    {
        return new Island($this->map, $name);
    }

    /**
     * @param Island $island
     */
    private function givenJojoIslandIsLinkedTo(Island $island)
    {
        $this->map->addLink(new Link($this->jojoIsland, $island));
    }

    /**
     * @param string $player
     * @param Island $island
     */
    private function givenLinkIsTakenBy($player, Island $island)
    {
        $this->jojoIsland->takeLink($player, $island);
    }
}
