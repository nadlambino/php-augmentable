<?php

declare(strict_types=1);

namespace Inspira\Augmentable;

use BadMethodCallException;
use Closure;
use Exception;
use ReflectionFunction;

/**
 * Trait Augmentable
 *
 * The Augmentable trait allows for the dynamic addition of methods to a class.
 *
 * @package Inspira\Augmentable
 */
trait Augmentable
{
	/**
	 * @var Closure[]|callable[] Stores dynamically added methods.
	 */
	private static array $augments = [];

	/**
	 * Add a dynamically augmented method.
	 *
	 * @param string $method The name of the method.
	 * @param Closure|callable $handler The closure or callable representing the method.
	 *
	 * @return void
	 */
	public static function augment(string $method, Closure|callable $handler): void
	{
		self::$augments[$method] = $handler;
	}

	/**
	 * Check if a dynamically added method exists.
	 *
	 * @param string $method The name of the method.
	 *
	 * @return bool
	 */
	public static function augmented(string $method): bool
	{
		return isset(self::$augments[$method]);
	}

	/**
	 * Get the dynamically added methods (augments) available in the class.
	 *
	 * @return array An array containing the names of dynamically added methods.
	 */
	public static function augments(): array
	{
		return self::$augments;
	}

	/**
	 * Handle calls to dynamic methods.
	 *
	 * @param string $method The name of the method.
	 * @param array $arguments The arguments passed to the method.
	 *
	 * @return mixed The result of the method call.
	 *
	 * @throws BadMethodCallException When the dynamic method does not exist.
	 */
	public function __call(string $method, array $arguments)
	{
		if (!self::augmented($method)) {
			throw new BadMethodCallException("Method [$method] does not exist.");
		}

		$handler = self::$augments[$method];

		if ($handler instanceof Closure && $this->canBeBind($handler)) {
			return $handler->call($this, ...$arguments);
		}

		return $handler(...$arguments);
	}

	/**
	 * Handle calls to dynamic static methods.
	 *
	 * @param string $method The name of the method.
	 * @param array $arguments The arguments passed to the method.
	 *
	 * @return mixed The result of the static method call.
	 *
	 * @throws BadMethodCallException When the dynamic static method does not exist.
	 */
	public static function __callStatic(string $method, array $arguments)
	{
		if (!self::augmented($method)) {
			throw new BadMethodCallException("Method [$method] does not exist.");
		}

		$handler = self::$augments[$method];

		if ($handler instanceof Closure && self::canBeBind($handler)) {
			return $handler->call(new self(), ...$arguments);
		}

		return $handler(...$arguments);
	}

	/**
	 * Determine if a closure can be safely bound to an object.
	 *
	 * This method checks if a closure can be bound by examining its reflection.
	 * A closure can be safely bound if it has no closure scope class or if the closure's
	 * `$this` parameter is not null.
	 *
	 * @param Closure $handler The closure to be checked.
	 *
	 * @return bool Returns true if the closure can be safely bound, false otherwise.
	 */
	protected static function canBeBind(Closure $handler): bool
	{
		try {
			$reflectionFunction = new ReflectionFunction($handler);

			return $reflectionFunction->getClosureScopeClass() === null || $reflectionFunction->getClosureThis() !== null;
		} catch (Exception) {
			return false;
		}
	}
}
