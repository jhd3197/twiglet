# twiglet

Twiglet is a tiny, file-based PHP template renderer with variable replacement and pipe-style filters.

## Quick start

```sh
php test.php
php tests/run.php
```


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

## String templates

```php
$template = "<p>{{ title }}</p><p>{{ text | upper }}</p>";

echo $twiglet->render_string($template, [
    'title' => 'Hello',
    'text' => 'twiglet',
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
- upper (uppercase a string)
- lower (lowercase a string)
- length (strings and arrays)
- trim (trim whitespace)
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

## Filter list (API)

```php
$filters = $twiglet->list_filters();
print_r($filters);
```

Example output:

```
Array
(
    [upper] => Uppercase a string.
    [lower] => Lowercase a string.
    [length] => Length of a string or array.
    ...
)
```

## Custom filters

```php
$twiglet->add_filter('wrap', function ($value, $left = '[', $right = ']') {
    return $left . (string) $value . $right;
}, 'Wrap a value with left and right strings.');
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

- No loops or conditionals (no if/elif/else or for/foreach)
- No Jinja/Twig blocks or tags
- No auto-escaping
- Filters use comma-separated args

## Ideas for next filters

- replace
- slice
- slug
- json
- date
- number_format
- reverse
