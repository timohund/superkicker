<?php

namespace Ts\Superkicker\SuperkickerBundle;

use Doctrine\DBAL\Types\Type;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Webforge\Doctrine\Types\DateTimeType;


/**
 * Class SuperkickerBundle
 * @package TS\Superkicker\SuperkickerBundle
 */
class SuperkickerBundle extends Bundle
{
	public function boot()
	{
		if(!Type::hasType('WebforgeDateTime')) {
			Type::addType('WebforgeDateTime', 'Webforge\Doctrine\Types\DateTimeType');
		}
	}
}
