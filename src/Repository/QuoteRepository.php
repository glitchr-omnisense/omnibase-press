<?php

namespace Base\Press\Repository;

use Base\Press\Entity\Quote;
use Base\Press\Enum\QuoteKind;
use Base\Database\Repository\Finder\Criterion;
use Base\Database\Repository\Finder\Group;
use Base\Database\Repository\Finder\Parser;
use Base\Database\Repository\Finders;
use Base\Database\Repository\ServiceEntityRepository;

/**
 * @method Quote|null find($id, $lockMode = null, $lockVersion = null)
 * @method Quote|null findOneBy(array $criteria, ?array $orderBy = null)
 * @method Quote[]    findAll()
 * @method Quote[]    findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 */
class QuoteRepository extends ServiceEntityRepository
{
    public function __construct(Finders $finders)
    {
        parent::__construct($finders, Quote::class);
    }

    /**
     * The quotes shown to a reader of $locale - written in it, or in no
     * language in particular -, the featured ones first, then in their order,
     * the newest first among equals. No locale: every language.
     *
     * @return list<Quote>
     */
    public function findVisible(?string $locale = null, ?QuoteKind $kind = null, ?int $limit = null, bool $featuredOnly = false): array
    {
        $criteria = ['visible' => true];
        if ($locale) {
            $criteria[] = Group::of(Parser::SEPARATOR_OR,
                Criterion::of('locale', mb_strtolower(substr($locale, 0, 2))),
                Criterion::of('locale', null, Parser::OPTION_NULL),
            );
        }
        if ($kind) {
            $criteria['kind'] = $kind;
        }
        if ($featuredOnly) {
            $criteria['featured'] = true;
        }

        return $this->findBy($criteria, ['featured' => 'DESC', 'position' => 'ASC', 'date' => 'DESC', 'id' => 'DESC'], $limit ?: null);
    }

    /**
     * The same, falling back on another language (English) when the reader's
     * has nothing: a German page with no German quote shows the English ones
     * rather than none.
     *
     * @return list<Quote>
     */
    public function findForLocale(string $locale, string $fallback = 'en', ?QuoteKind $kind = null, ?int $limit = null, bool $featuredOnly = false): array
    {
        $quotes = $this->findVisible($locale, $kind, $limit, $featuredOnly);
        if (!$quotes && mb_strtolower(substr($locale, 0, 2)) !== $fallback) {
            $quotes = $this->findVisible($fallback, $kind, $limit, $featuredOnly);
        }

        return $quotes;
    }
}
