<?php

namespace Ts\Superkicker\SuperkickerBundle\Domain\Service;

use \Ts\Superkicker\SuperkickerBundle\Domain\Model\Ranking;

/**
 * Created by PhpStorm.
 * User: timoschmidt
 * Date: 17.07.15
 * Time: 16:40
 */

class RankingSort {
	public static function cmp(Ranking $a, Ranking $b) {
		if ($a == $b) {
			return 0;
		}

		return ($a->getScore() > $b->getScore()) ? -1 : 1;
	}

	/**
	 * @param $ranking
	 */
	public static function sort($ranking) {
		usort($ranking,'self::cmp');

		return $ranking;
	}
}