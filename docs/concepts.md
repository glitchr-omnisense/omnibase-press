---
title: Quotes, photos and biographies
order: 20
---

# Quotes, photos and biographies

## `Quote`

`Base\Press\Entity\Quote` (`press_quote`, indexed on `visible`, `featured`, `position`):

| Property | Column | Meaning |
|---|---|---|
| `text` | `text`, not blank | the words; the quotation marks typed around them are dropped (the template adds its own) |
| `language` | `string(5)`, nullable | the language the words were said in - what "the original" is; falls back on `locale` |
| `translations` | `json`, nullable | per two-letter language: `{text, role}` - the words translated, the speaker's quality |
| `offerOriginal` | `boolean` (`offer_original`), `true` | whether a translated quote lets the reader see the original |
| `locale` | `string(5)`, nullable | restricts the quote to the readers of one language; `null`: shown to all |
| `author` | `string(160)`, nullable | who speaks ("Joshua Bell"); empty when only the paper signs |
| `role` | `string(160)`, nullable | what the author is ("violinist") |
| `source` | `string(160)`, nullable | the publication |
| `url` | `string(500)`, nullable | its link (a URL with a TLD) |
| `date` | `date_immutable` (`quoted_on`), nullable | |
| `kind` | `QuoteKind`, `PRESS` | `PRESS`, `COLLEAGUE`, `AUDIENCE`; an unknown value is `PRESS` |
| `featured` | `boolean` | among the few a home page shows |
| `position` | `integer` | |
| `release` | `string(200)` (`about_release`), nullable | what it is about: an album's title, a concert - free words |
| `visible` | `boolean`, `true` | |

Empty strings are kept as `null`. Methods:

- `getTextIn(string $locale)`, `getRoleIn(string $locale)` - the translation for a reader of
  that language, else the original words and role. `isTranslatedIn($locale)` - there is a
  translation and it is not the original's language.
- `setTranslation(string $locale, ?string $text, ?string $role = null)` - nothing in either
  removes it; `setTranslations(array)` takes `{fr: {text, role}}` or `{fr: 'words'}`.
- The magic properties `translation_<xx>` and `role_<xx>` read and write `translations` - the
  back office's fields, one pair per language of the site.
- `speaks(string $locale)` - no `locale`, or that one.
- `getSignature()` - "Joshua Bell, violinist — BBC Music Magazine"; the source is not repeated
  when the role or author already names it.
- `toggleFeatured()`.

## `Photo`

`Base\Press\Entity\Photo` (`press_photo`, indexed on `visible`, `position`):

| Property | Column | Meaning |
|---|---|---|
| `file` | upload, image, 64 MB | the original |
| `title` | `string(160)`, nullable | |
| `credit` | `string(160)`, nullable | the photographer, as a paper must print it |
| `caption` | `text`, nullable | |
| `downloadable` | `boolean`, `true` | the original may be taken |
| `position` | `integer` | |
| `visible` | `boolean`, `true` | in the press kit, on `/press` |
| `gallery` | `boolean`, `false` | in the site's own gallery (`press_gallery()`) |

A photograph not cleared for the press goes in the gallery only. `getFileUrl()` is the
address an `<img>` takes (`/uploads/...`: the storage's path with the public directory taken
off). `getAlt()` is the caption, else the title. `getCreditLine()` adds `© ` unless the credit
starts with `©`, `(c)`, `photo` or `foto`.

## `Bio`

`Base\Press\Entity\Bio` (`press_bio`, unique on `locale` and `bio_length`):

| Property | Column | Meaning |
|---|---|---|
| `locale` | `string(5)` | lowercased; `en` when empty |
| `length` | `BioLength` (`bio_length`) | `SHORT` (100 words), `MEDIUM` (250), `LONG` (no limit); an unknown value is `SHORT` |
| `content` | `text`, not blank | plain text, a blank line between two paragraphs; line ends normalised, trimmed |
| `updatedAt` | `datetime` | set on create and update (the core's `#[Timestamp]`): an agency sees the biography is current |

`getParagraphs()`, `getWordCount()`, `Bio::countWords(string)` - what stands between two
blanks, in any alphabet: "l'archet" and "Sinfonie-Orchester" are one word each, a lone dash
none. `isTooLong()` - more than the length's words plus ten percent (never for `LONG`).

## In memory

Run in omnisense/omnibase's harness image, with this checkout's `src/` on the autoloader:

```php
use Base\Press\Entity\Bio;
use Base\Press\Entity\Photo;
use Base\Press\Entity\Quote;
use Base\Press\Enum\BioLength;
use Base\Press\Service\PhotoFilename;

$quote = (new Quote('« A harp that sings. »', 'Joshua Bell', 'BBC Music Magazine'))->setRole('violinist')->setLanguage('en');
$quote->setTranslation('fr', 'Une harpe qui chante.', 'violoniste');
echo $quote->getText(), ' | ', $quote->getSignature(), "\n";
echo $quote->getTextIn('fr'), ' | ', $quote->getRoleIn('fr'), ' | ', $quote->getTextIn('de'), "\n";

$bio = new Bio('FR', BioLength::SHORT, str_repeat('mot ', 111));
var_dump($bio->getLocale(), $bio->getWordCount(), $bio->isTooLong());
echo Bio::countWords("l'archet du Sinfonie-Orchester - fin"), "\n";

echo (new Photo())->setCredit('Harald Hoffmann')->getCreditLine(), "\n";
echo PhotoFilename::of('Clara Weiss', 'Portrait with harp', '© Harald Hoffmann', 12, 'JPEG'), "\n";
echo PhotoFilename::of('clara-weiss.example', null, null, 12, 'png'), "\n";
```

```
A harp that sings. | Joshua Bell, violinist — BBC Music Magazine
Une harpe qui chante. | violoniste | A harp that sings.
string(2) "fr"
int(111)
bool(true)
4
© Harald Hoffmann
clara-weiss-portrait-with-harp.jpg
clara-weiss-example-photo-12.png
```

## Repositories

| Repository | Methods |
|---|---|
| `QuoteRepository` | `findVisible(?string $locale = null, ?QuoteKind $kind = null, ?int $limit = null, bool $featuredOnly = false)` - visible, of that language or none, featured first, then by position, newest first; `findForLocale(string $locale, string $fallback = 'en', ...)` - the same, falling back on English when the reader's language has nothing |
| `BioRepository` | `findOneFor(BioLength $length, string $locale, string $fallback = 'en')`; `findByLength(string $locale, array $lengths, string $fallback = 'en')` - length => biography, a length nobody wrote left out |
| `PhotoRepository` | `findVisible(?int $limit = null)` (the press kit), `findGallery(?int $limit = null)` (the site's gallery), by position |
