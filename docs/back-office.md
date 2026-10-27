---
title: The back office
order: 38
---

# The back office

Loaded when omnibase/admin is installed. The three screens carry omnibase/admin's
`#[OpenToAdmins]`: the site's administrator (`ROLE_ADMIN`) creates, edits and deletes, not only
the super-administrator - and, on quotes, uses the feature button.

| CRUD | Slug | Fields |
|---|---|---|
| `QuoteCrudController` | `quotes` | text, author, role, source, link, date, about, kind, restricted to a language, the original's language, offer the original, then per language of the site a translation and the speaker's quality, position, featured, visible; filters kind, featured, language |
| `PhotoCrudController` | `photos` | the original, title, credit, caption, position, downloadable, in the press kit, in the gallery; filters downloadable, visible, gallery |
| `BioCrudController` | `bios` | language, length, words (list), updated (list), text; filters language, length |

The languages are `kernel.enabled_locales`, else `en`, `fr`, `de`.

**Feature** (`/admin/quotes/{entityId}/feature`, on the list and on a quote) toggles `featured`
and says which way it went.

The biography's text has a live word counter under it: the same words `Bio::countWords()`
counts, shown against 100 or 250 for the length chosen, in red past ten percent more.
