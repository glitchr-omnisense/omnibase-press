---
title: The page and the downloads
order: 30
---

# The page and the downloads

## `/press`

`Base\Press\Controller\Client\PressController::index()` renders `@Press/client/index.html.twig`:

| Variable | Content |
|---|---|
| `quotes` | the featured quotes of the reader's language (else English); a site that featured none shows them all |
| `bios` | `press.bio_lengths` in order, each in the reader's language or English, each with its "copy" button; a length nobody wrote is left out |
| `photos` | the press kit's pictures (`visible`), their credit, and their original to download when allowed |
| `contacts` | `press.contacts` |
| `download_kit` | `press.download_kit` |

The languages: a quote is for a reader of its `locale`, or of every language when it has none;
its words are the reader's translation when there is one (with the original on demand when
`offerOriginal`). The fallback language is `PressController::FALLBACK_LOCALE`, `en`.

## Downloading an original

`GET /press/photo/{id}/download` sends the original as an attachment, with
`X-Robots-Tag: noindex`. A 404 when the picture does not exist, is not in the press kit, is not
downloadable, has no file, or `press.download_kit` is off.

The file is named by `Base\Press\Service\PhotoFilename::of($site, $title, $credit, $id, $extension)`:
`<site>-<slug>.<ext>` - the site's title (`base.settings.title`, else the host without `www.`),
the picture's title, else its credit without its sign, else `photo-<id>`; slugged in ASCII and
lowercased; `jpeg` written `jpg`; an extension cannot carry a path. The file in a journalist's
folder still says whose it is.

## Twig functions

`Base\Press\Twig\PressExtension`, in the current request's language:

| Function | Returns |
|---|---|
| `press_quotes(limit = 3, kind = null)` | the visible quotes, featured first, falling back on English; `kind`: `press`, `colleague`, `audience` |
| `press_bio(length = 'short', locale = null)` | one biography (`.content`, `.paragraphs`, `.wordCount`, `.updatedAt`), in the language or English, or `null` |
| `press_photos(limit = null)` | the press kit's pictures (`.fileUrl`, `.alt`, `.caption`, `.creditLine`) |
| `press_gallery(limit = null)` | the pictures of the site's gallery, in the press kit or not |
| `press_contacts()` | `press.contacts` |

```twig
{% for quote in press_quotes(2) %}
    {% include '@Press/client/_quote.html.twig' with {quote: quote} %}
{% endfor %}

{% set bio = press_bio('medium') %}
{% if bio %}{% for paragraph in bio.paragraphs %}<p>{{ paragraph }}</p>{% endfor %}{% endif %}
```

## Templates and look

`@Press/client/`: `index`, `_quote` (one quote), `_quote_words`. An application overrides them in
`templates/bundles/PressBundle/client/...`. `bundles/press/css/press.css` follows the host's
`--press-accent`, `--press-ink`, `--press-soft`, `--press-line`, `--press-surface` and
`--press-measure` when it defines them.

Translations: the domain `press` (ICU), in English, French and German.
