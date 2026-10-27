---
title: Installation
order: 10
---

# Installation

## Requirements

What `composer.json` requires: PHP 8.2 or later, `doctrine/orm` 2.15 or 3,
`omnisense/omnibase` 3.0 or 4.0, `omnibridge/symfony` 1.x, `omnibase/admin`, and
`symfony/config`, `dependency-injection`, `form`, `http-kernel`, `security-bundle` and
`string` 6, 7 or 8. The back office's screens use omnibase/admin's `#[OpenToAdmins]`
attribute (omnibase/admin from 7474f85).

## The bundle

```bash
composer require omnibase/press:dev-main
```

```php
// config/bundles.php
return [
    // ...
    Base\BaseBundle::class => ['all' => true],
    Base\Admin\AdminBundle::class => ['all' => true],
    Base\Press\PressBundle::class => ['all' => true],
];
```

`PressBundle` extends the core's `AbstractBaseBundle`: its root is the package's
(`templates/` is `@Press`, `public/` is `bundles/press/`), and `build()` aliases
`Base\Press\Entity\*` to `App\Entity\Press\*` and the repositories to `App\Repository\Press\*`:
an application takes an entity over by declaring `App\Entity\Press\Quote extends
Base\Press\Entity\Quote`.

## The routes

```yaml
# config/routes.yaml
press_controller:
    resource: "@PressBundle/src/Controller/Client"
    type: attribute
    prefix: /
```

| Route | Path | Methods | |
|---|---|---|---|
| `press_index` | `/press` | GET | the page; in the sitemap (0.6, monthly) |
| `press_photo_download` | `/press/photo/{id}/download` | GET | the original as an attachment |

## Configuration

```yaml
# config/packages/press.yaml (every key optional)
press:
    download_kit: true
    bio_lengths: [short, medium, long]
    contacts:
        - name: "Anna Keller"
          role: "General management"
          organisation: "Keller Artists"
          email: "anna@keller-artists.example"
          phone: "+49 30 000 00 00"
          url: "https://keller-artists.example"
```

## The schema and the public files

```bash
bin/console doctrine:migrations:diff
bin/console doctrine:migrations:migrate
bin/console assets:install
```

Tables `press_quote`, `press_photo`, `press_bio`; the stylesheet `bundles/press/css/press.css`.

## What the layout provides

The page extends `layout1.html.twig` and fills `title`, `description`, `content` and
`stylesheets`.
