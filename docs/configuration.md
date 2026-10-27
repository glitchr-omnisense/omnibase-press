---
title: Configuration reference
order: 40
---

# Configuration reference

## `press:`

`Base\Press\DependencyInjection\PressConfiguration`, dumped with Symfony's `YamlReferenceDumper`
in omnisense/omnibase's harness image:

```yaml
press:

    # Who the press writes to: the agency, the label's PR, the artist.
    contacts:

        # Prototype
        -
            name:                 ~ # Required

            # "General management", "Press, Germany"...
            role:                 null
            email:                null
            phone:                null
            organisation:         null
            url:                  null

    # The HD downloads at all: off, the photos are shown and none can be taken.
    download_kit:         true

    # The biographies the page shows, in this order.
    bio_lengths:

        # Defaults:
        - short
        - medium
        - long
```

| Option | Type | Default | Effect |
|---|---|---|---|
| `contacts` | list of `{name, role, email, phone, organisation, url}` | `[]` | the page's contacts and `press_contacts()`; `name` is required and not empty |
| `download_kit` | bool | `true` | `false`: the pictures are shown, none can be downloaded |
| `bio_lengths` | list of `short`, `medium`, `long` | all three | the biographies `/press` shows, in this order |

The parameters are `press.contacts` (read whole), `press.download_kit`, `press.bio_lengths`
(and the flattened leaves of each).

## Services

`config/services.php` autowires and autoconfigures every class of `src/` but
`DependencyInjection/`, `Entity/`, `Enum/`, `Controller/Admin/` and the bundle class;
`Controller/Client/` is tagged `controller.service_arguments`; with omnibase/admin,
`Controller/Admin/` too. `PressController` and `PressExtension` read the parameters with
`#[Autowire]`; `PressController` reads `base.settings.title` from the core's setting bag.
