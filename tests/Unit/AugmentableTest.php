<?php

use Inspira\Augmentable\tests\Fixtures\Person;

beforeEach(function () {
	Person::deaugment();
});

it('can augment the class', function () {
	Person::augment('getName', function () {
		return $this->name;
	});

	expect(Person::augmented('getName'))->toBeTrue();
});

it('can call the augmented method', function () {
	Person::augment('getName', function () {
		return $this->name;
	});

	$name = 'John Doe';
	$person = new Person($name);

	expect($person->getName())->toBe($name);
});

it('can call the augmented method statically', function () {
	Person::augment('create', function (string $name) {
		return new self($name);
	});
	Person::augment('getName', function () {
		return $this->name;
	});

	$name = 'Jane Doe';
	$person = Person::create($name);

	expect($person)->toBeInstanceOf(Person::class);
	expect($person->getName())->toBe($name);
});

it('can accept a callable handler', function () {
	Person::augment('capitalizeName', 'ucwords');
	Person::augment('getName', function () {
		return $this->name;
	});

	$name = 'john doe';
	$person = new Person($name);
	$capitalizedName = $person->capitalizeName($person->getName());

	expect($capitalizedName)->toBe(ucwords($name));
});

it('returns an array of all augmented methods', function () {
	Person::augment('capitalizeName', 'ucwords');
	Person::augment('getName', function () {
		return $this->name;
	});

	$methods = Person::augments();

	expect($methods)->toHaveKeys(['capitalizeName', 'getName']);
});
