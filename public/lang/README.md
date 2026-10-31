# Translations

The language is set with `LANGUAGE` in `common/config.php` (for example `const LANGUAGE = 'en';`).
If it is not set, or there is no folder for it here, Dutch (`nl`) is used.

Each language has its own folder:

- `messages.php`: all interface texts, as `key => text`. Placeholders like `{title}` are filled in by the code.
  Keys starting with `js.` are also available in JavaScript through `i18n('key')` (without the `js.` prefix).
  `meta.locale` and the `date.*` patterns ([ICU format](https://unicode-org.github.io/icu/userguide/format_parse/datetime/)) control how dates are shown.
- `mail/*.txt`: mail bodies (Handlebars templates, e.g. `{{name}}`).
- `privacy.php`: the privacy statement.

Dutch is the source language and must contain every key. A new language only needs the keys,
mails and pages it translates; anything missing falls back to the Dutch version.

To add a language, create `lang/<code>/messages.php` returning an array with the translated keys,
and optionally translated copies of the `mail/` templates and `privacy.php`.
