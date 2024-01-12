<?php

namespace Inspira\Augmentable\tests\Fixtures;

use Inspira\Augmentable\Augmentable;

class Person
{
	use Augmentable;

	public function __construct(protected string $name)
	{

	}
}
