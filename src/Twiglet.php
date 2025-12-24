<?php

namespace Twiglet;

class Twiglet {

    protected array $filters = [];

    public function __construct() {
        $this->register_default_filters();
    }

    public function render(string $template_path, array $vars = []): string {
        if (!is_readable($template_path)) {
            return '';
        }

        $template = file_get_contents($template_path);

        return preg_replace_callback(
            '/{{\s*(.*?)\s*}}/',
            function ($matches) use ($vars) {
                return $this->evaluate_expression($matches[1], $vars);
            },
            $template
        );
    }

    public function add_filter(string $name, callable $callback): void {
        $this->filters[$name] = $callback;
    }

    protected function evaluate_expression(string $expression, array $vars): string {
        $segments = array_map('trim', explode('|', $expression));
        $value = $this->resolve_variable(array_shift($segments), $vars);

        foreach ($segments as $segment) {
            $value = $this->apply_filter($segment, $value);
        }

        return $this->stringify($value);
    }

    protected function resolve_variable(string $key, array $vars) {
        $parts = explode('.', trim($key));
        $value = $vars;

        foreach ($parts as $part) {
            if (is_array($value) && array_key_exists($part, $value)) {
                $value = $value[$part];
            } else {
                return '';
            }
        }

        return $value;
    }

    protected function apply_filter(string $filter, $value) {
        if (preg_match('/^(\w+)\((.*?)\)$/', $filter, $matches)) {
            $name = $matches[1];
            $args = $this->parse_args($matches[2]);
        } else {
            $name = $filter;
            $args = [];
        }

        if (!isset($this->filters[$name])) {
            return $value;
        }

        return call_user_func($this->filters[$name], $value, ...$args);
    }

    protected function parse_args(string $args): array {
        if ($args === '') {
            return [];
        }

        $parts = [];
        $current = '';
        $in_single = false;
        $in_double = false;
        $length = strlen($args);

        for ($i = 0; $i < $length; $i++) {
            $char = $args[$i];

            if ($char === "'" && !$in_double) {
                $in_single = !$in_single;
                $current .= $char;
                continue;
            }

            if ($char === '"' && !$in_single) {
                $in_double = !$in_double;
                $current .= $char;
                continue;
            }

            if ($char === ',' && !$in_single && !$in_double) {
                $parts[] = $current;
                $current = '';
                continue;
            }

            $current .= $char;
        }

        $parts[] = $current;

        return array_map(function ($arg) {
            $arg = trim($arg);

            if (
                (str_starts_with($arg, '"') && str_ends_with($arg, '"')) ||
                (str_starts_with($arg, "'") && str_ends_with($arg, "'"))
            ) {
                return substr($arg, 1, -1);
            }

            if (is_numeric($arg)) {
                return $arg + 0;
            }

            return $arg;
        }, $parts);
    }

    protected function stringify($value): string {
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if ($value === null) {
            return '';
        }

        if (is_array($value) || is_object($value)) {
            return '';
        }

        return (string) $value;
    }

    protected function register_default_filters(): void {
        $this->filters['truncate'] = function ($value, int $length = 100, string $suffix = '…') {
            $value = trim(strip_tags((string) $value));

            if (function_exists('mb_strlen')) {
                if (\mb_strlen($value) <= $length) {
                    return $value;
                }

                return \mb_substr($value, 0, $length) . $suffix;
            }

            // Fallback if mbstring is not available
            if (strlen($value) <= $length) {
                return $value;
            }

            return substr($value, 0, $length) . $suffix;
        };

        $this->filters['upper'] = fn($v) => strtoupper((string) $v);
        $this->filters['lower'] = fn($v) => strtolower((string) $v);
        $this->filters['length'] = function ($v) {
            if (is_string($v)) {
                return strlen($v);
            }

            if (is_array($v)) {
                return count($v);
            }

            if ($v instanceof \Countable) {
                return count($v);
            }

            return 0;
        };
        $this->filters['trim'] = fn($v) => trim((string) $v);
        $this->filters['title'] = function ($v) {
            $value = (string) $v;

            if (function_exists('mb_convert_case')) {
                return mb_convert_case($value, MB_CASE_TITLE, 'UTF-8');
            }

            return ucwords(strtolower($value));
        };
        $this->filters['split'] = function ($value, string $delimiter = ' ', int $limit = 0) {
            $value = (string) $value;

            if ($limit != 0) {
                return explode($delimiter, $value, $limit);
            }

            return explode($delimiter, $value);
        };
        $this->filters['join'] = function ($value, string $delimiter = '') {
            if (is_array($value)) {
                return implode($delimiter, $value);
            }

            return (string) $value;
        };

        $this->filters['default'] = function ($value, $fallback = '') {
            return $value === '' ? $fallback : $value;
        };
    }
}
