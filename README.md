# twiglet

Twiglet is a tiny, file-based PHP template renderer with variable replacement and pipe-style filters.

## Quick start

```sh
php test.php
php tests/run.php
```

## Basic usage

```php
<?php

use Twiglet\Twiglet;

$twiglet = new Twiglet();

echo $twiglet->render(__DIR__ . '/views/test.html', [
    'title' => 'Hello',
    'enabled' => true,
    'post' => [
        'title' => 'Twiglet',
    ],
]);
```

## Variables and dot notation

```twig
{{ title }}
{{ post.title }}
{{ post.meta.author }}
```

Missing keys render as an empty string.

## Built-in filters

- truncate(length=100, suffix defaults to the ellipsis character)
- default(fallback='')
- upper
- lower
- length (strings and arrays)
- trim
- title (simple title case)
- split(delimiter=' ', limit=0)
- join(delimiter='')

## Filter usage

```twig
{{ title | upper }}
{{ title | trim | title }}
{{ text | truncate(50, "...") }}
{{ csv | split(",") | join(" / ") }}
{{ csv | split(",") | length }}
```

## Custom filters

```php
$twiglet->add_filter('wrap', function ($value, $left = '[', $right = ']') {
    return $left . (string) $value . $right;
});
```

Template:

```twig
{{ name | wrap("(", ")") }}
```

## Tests

```sh
php tests/run.php
```

## Limitations

- No loops or conditionals
- No auto-escaping
- Filters use comma-separated args

## Ideas for next filters

- replace
- slice
- slug
- json
- date
- number_format
