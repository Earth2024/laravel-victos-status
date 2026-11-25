<?php

namespace Earth2024\VictosStatus\Support;

use Illuminate\Validation\ValidationException;

class VictosDomains
{
    public static function fromText(string $value): array
    {
        $domains = [];

        foreach (preg_split('/[\r\n,]+/', $value) as $entry) {
            $entry = strtolower(trim($entry));
            if ($entry === '') {
                continue;
            }

            if (str_contains($entry, '://')) {
                $parts = parse_url($entry);
                $host = $parts['host'] ?? '';
                $port = $parts['port'] ?? null;
                if (!empty($parts['path']) && $parts['path'] !== '/') {
                    $host = '';
                }
            } else {
                $parts = parse_url('https://' . $entry);
                $host = $parts['host'] ?? '';
                $port = $parts['port'] ?? null;
                if (!empty($parts['path']) && $parts['path'] !== '/') {
                    $host = '';
                }
            }

            $validHost = $host === 'localhost'
                || filter_var($host, FILTER_VALIDATE_IP) !== false
                || (bool) filter_var($host, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME);
            if (!$validHost || ($port !== null && ($port < 1 || $port > 65535))) {
                throw ValidationException::withMessages([
                    'allowed_domains_text' => "Enter a valid domain or host:port. Invalid entry: {$entry}",
                ]);
            }

            $domain = strtolower($host) . ($port !== null && !in_array((int) $port, [80, 443], true) ? ':' . $port : '');
            $domains[$domain] = $domain;
        }

        return array_values($domains);
    }

    public static function toText(mixed $domains): string
    {
        if (is_string($domains)) {
            $domains = json_decode($domains, true) ?: [];
        }

        if (!is_array($domains)) {
            $domains = [];
        }

        return implode(PHP_EOL, $domains ?? []);
    }
}
