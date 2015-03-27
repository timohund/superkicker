<?php

namespace Ts\Superkicker\SuperkickerBundle\Domain\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Ts\Superkicker\SuperkickerBundle\Domain\Model\Match;
use Ts\Superkicker\SuperkickerBundle\Domain\Model\SoccerClub;
use Webforge\Common\DateTime\DateTime;

class MatchRepository extends AbstractRepository{

	/**
	 * @return \Doctrine\ORM\PersistentCollection<Ts\Superkicker\SuperkickerBundle\Match>
	 */
	public function findByMatchDay() {
		$result = new ArrayCollection();

		$bvb = new SoccerClub();
		$bvb->setName('BVB');
		$bvb->setId(1);

		$fcb = new SoccerClub();
		$fcb->setName('FC Bayern München');
		$fcb->setId(2);

		$matchOne = new Match();
		$matchOne->setId(1);
		$matchOne->setHomeSoccerClub($bvb);
		$matchOne->setGuestSoccerClub($fcb);
		$matchOne->setHomeScore(0);
		$matchOne->setGuestScore(0);
		$matchOne->setDate(new DateTime('now'));
		$matchOne->setMatchDay(0);

		#########

		$koeln = new SoccerClub();
		$koeln->setName('1. FC Köln');
		$koeln->setId(3);

		$mainz = new SoccerClub();
		$mainz->setName('1. FC Mainz');
		$mainz->setId(4);

		$matchTwo = new Match();
		$matchTwo->setId(2);
		$matchTwo->setHomeSoccerClub($koeln);
		$matchTwo->setGuestSoccerClub($mainz);
		$matchTwo->setHomeScore(0);
		$matchTwo->setGuestScore(0);
		$matchTwo->setDate(new DateTime('now'));
		$matchTwo->setMatchDay(0);


		$result->add($matchOne);
		$result->add($matchTwo);

		return $result;
	}

	public function findById($id) {
		$bvb = new SoccerClub();
		$bvb->setName('BVB');
		$bvb->setId(1);

		$fcb = new SoccerClub();
		$fcb->setName('FC Bayern München');
		$fcb->setId(2);

		$matchOne = new Match();
		$matchOne->setId(1);
		$matchOne->setHomeSoccerClub($bvb);
		$matchOne->setGuestSoccerClub($fcb);
		$matchOne->setHomeScore(0);
		$matchOne->setGuestScore(0);
		$matchOne->setDate(new DateTime('now'));
		$matchOne->setMatchDay(0);


		#########

		$koeln = new SoccerClub();
		$koeln->setName('1. FC Köln');
		$koeln->setId(3);

		$mainz = new SoccerClub();
		$mainz->setName('1. FC Mainz');
		$mainz->setId(4);

		$matchTwo = new Match();
		$matchTwo->setId(2);
		$matchTwo->setHomeSoccerClub($koeln);
		$matchTwo->setGuestSoccerClub($mainz);
		$matchTwo->setHomeScore(0);
		$matchTwo->setGuestScore(0);
		$matchTwo->setDate(new DateTime('now'));
		$matchTwo->setMatchDay(0);

		if($id == 1) {
			return $matchOne;
		} elseif ($id == 2) {
			return $matchTwo;
		}
	}

}