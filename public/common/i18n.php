<?php

// Translations live in /lang/<language>/. Dutch is the source language: every
// key exists in lang/nl/messages.php, and any key, mail or page that is missing
// in the configured language falls back to the Dutch version.

const DEFAULT_LANGUAGE = 'nl';

function currentLanguage(): string
{
    static $language = null;
    if ($language === null) {
        $language = DEFAULT_LANGUAGE;
        if (defined('LANGUAGE') && preg_match('/^[a-z]{2,3}(-[a-z0-9]+)?$/i', LANGUAGE)) {
            if (is_dir(ABSPATH . '/lang/' . LANGUAGE)) {
                $language = LANGUAGE;
            }
        }
    }
    return $language;
}

/**
 * Returns the path of a file in the language folder, falling back to Dutch.
 */
function langFile(string $file): string
{
    $path = ABSPATH . '/lang/' . currentLanguage() . '/' . $file;
    if (!is_file($path)) {
        $path = ABSPATH . '/lang/' . DEFAULT_LANGUAGE . '/' . $file;
    }
    return $path;
}

function translations(): array
{
    static $messages = null;
    if ($messages === null) {
        $messages = require ABSPATH . '/lang/' . DEFAULT_LANGUAGE . '/messages.php';
        if (currentLanguage() !== DEFAULT_LANGUAGE) {
            $path = ABSPATH . '/lang/' . currentLanguage() . '/messages.php';
            if (is_file($path)) {
                $messages = array_replace($messages, require $path);
            }
        }
    }
    return $messages;
}

/**
 * Translates a key. Placeholders like {name} are replaced with $params['name'].
 */
function t(string $key, array $params = []): string
{
    $text = translations()[$key] ?? $key;
    if (!empty($params)) {
        $replace = [];
        foreach ($params as $name => $value) {
            $replace['{' . $name . '}'] = (string) $value;
        }
        $text = strtr($text, $replace);
    }
    return $text;
}

/**
 * Locale used for date formatting, e.g. nl_NL.
 */
function currentLocale(): string
{
    return t('meta.locale');
}

function formatDate(int|string $timestamp, string $patternKey = 'date.format'): string
{
    $formatter = new IntlDateFormatter(
        currentLocale(),
        IntlDateFormatter::NONE,
        IntlDateFormatter::NONE,
        null,
        null,
        t($patternKey)
    );
    return $formatter->format((int) $timestamp);
}

/**
 * Returns the raw (Handlebars) mail template, e.g. mailTemplate('new_upload').
 */
function mailTemplate(string $name): string
{
    return file_get_contents(langFile("mail/$name.txt"));
}

/**
 * Translations that are needed in JavaScript (all keys starting with "js.").
 */
function jsTranslations(): array
{
    $result = [];
    foreach (translations() as $key => $text) {
        if (str_starts_with($key, 'js.')) {
            $result[substr($key, 3)] = $text;
        }
    }
    return $result;
}
