# Augmentable Trait

The `Augmentable` trait allows for the dynamic addition of methods to a class.

## Installation

Install the `Augmentable` trait using composer:

```bash
composer require inspira/augmentable-trait
```

## Usage

### Basic Example

```php
use Inspira\Augmentable\Augmentable;

class ExampleClass {
    use Augmentable;
}

// Dynamically add a new method named 'customMethod'
ExampleClass::augment('customMethod', function () {
    return 'Custom method implementation';
});

// Use the dynamically added method
$instance = new ExampleClass();
$result = $instance->customMethod(); // Outputs: 'Custom method implementation'
```

### Checking for Dynamic Methods

```php
use Inspira\Augmentable\Augmentable;

class ExampleClass {
    use Augmentable;
}

// Check if a dynamic method named 'customMethod' exists
if (ExampleClass::augmented('customMethod')) {
    $instance = new ExampleClass();
    $result = $instance->customMethod(); // Call the method if it exists
} else {
    // Handle the case when the dynamic method does not exist
    echo 'Dynamic method does not exist.';
}
```

### Listing All Dynamic Methods

```php
use Inspira\Augmentable\Augmentable;

class ExampleClass {
    use Augmentable;
}

// Get an array of all dynamically added methods
$dynamicMethods = ExampleClass::augments();
print_r($dynamicMethods);
```
