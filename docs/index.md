# omnibase/press

The press kit for the sites built on [omnisense/omnibase](https://github.com/glitchr-omnisense/omnibase):
what an artist's agency - or a presenter's programme editor - asks a site for, on one page,
`/press`. It is a Symfony bundle (`Base\Press\PressBundle`) on the core 3 or 4, with its back
office on omnibase/admin. It was first made for two musicians' sites and holds nothing that is
theirs alone.

- A `Quote` is what was said or written: the words, who said them, where, what about, its kind
  (press, colleague, audience), with its translations beside the original words.
- A `Photo` is the original as the photographer delivered it (64 MB at most), its credit, a
  caption, whether the original may be downloaded, and where it is shown: the press kit, the
  site's own gallery, or both.
- A `Bio` is the biography once per language and per length: `short` (about 100 words),
  `medium` (about 250), `long`.
- The contacts - the agency, the label's press office - are configuration.

Use it for a performer's, a speaker's or an author's site. It is not a press-release mailer.

## How this manual is organised

- [Installation](installation.md): requirements, the bundle, the routes, the schema, the
  layout.
- [Quotes, photos and biographies](concepts.md): the entities, their fields and methods, the
  queries, an in-memory example.
- [The page and the downloads](usage-pages.md): `/press`, the download of an original, the
  languages, the Twig functions, the templates and the stylesheet.
- [The back office](back-office.md): the three screens, who writes, the feature button, the
  word counter.
- [Configuration reference](configuration.md): `press:`, the parameters, the services.
- [Testing](testing.md): the suite and what it checks.
