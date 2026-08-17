<?php
declare(strict_types=1);

final class Validate
{
    /**
     * Parsing input multi-email menjadi daftar email unik yang valid.
     * Mendukung pemisah baris, koma, dan titik koma.
     */
    public static function emails(string $raw): array
    {
        $domains = array_filter(array_map('trim', explode(',', (string) Config::get('ALLOWED_DOMAINS', ''))));
        $parts = preg_split('/[\r\n,;]+/', $raw);
        if ($parts === false) {
            return [];
        }
        $emails = [];
        foreach ($parts as $part) {
            $email = strtolower(trim($part));
            if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                continue;
            }
            if ($domains !== []) {
                $domain = substr(strrchr($email, '@') ?: '@', 1);
                if (!in_array(strtolower($domain), $domains, true)) {
                    continue;
                }
            }
            $emails[$email] = $email;
        }
        return array_values($emails);
    }
}
