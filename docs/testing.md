---
title: Testing
order: 60
---

# Testing

```bash
vendor/bin/phpunit                                                    # checked out on its own
php vendor/bin/phpunit -c vendor/omnibase/press/phpunit.xml.dist      # from an application
```

In omnisense/omnibase's harness, this checkout mounted over the package (the harness registers
the press bundle for this suite):

```bash
docker run --rm --network none -v "$PWD":/srv/demo/vendor/omnibase/press:ro omnisense/omnibase-harness test press
```

```
..................................                                34 / 34 (100%)

OK (34 tests, 142 assertions)
```

| File | What |
|---|---|
| `Entity/QuoteTest` | quotation marks typed around the text go; a default quote is a press quote, online, not featured; the feature button toggles it; the kind from its value, an unknown one is press; the signature names author, role and source once; a quote with no locale speaks to everyone, a restricted one to its language; empty strings kept as nothing; the date kept immutable; as a string, its first words; a reader gets the words in their language and may ask for the original |
| `Entity/BioTest` | words are what stands between two blanks; a biography's count is its content's; paragraphs; trimmed content, an empty one is nothing; a short biography too long past 110 words; each length's words; the locale lowercased, an unknown length is short |
| `Service/PhotoFilenameTest` | site, then title, then extension; accents and signs spelled out; without a title the credit without its sign; with neither, its number; an extension cannot carry a path |
| `Controller/Admin/OpenToAdminsTest` | an administrator writes in the back office, someone else is refused (inside a host application, skipped elsewhere) |
| `Controller/CrudRequiredFieldsTest` | the CRUDs' required fields |
