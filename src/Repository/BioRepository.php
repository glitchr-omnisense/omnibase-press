<?php

namespace Base\Press\Repository;

use Base\Press\Entity\Bio;
use Base\Press\Enum\BioLength;
use Base\Database\Repository\Finders;
use Base\Database\Repository\ServiceEntityRepository;

/**
 * @method Bio|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bio|null findOneBy(array $criteria, ?array $orderBy = null)
 * @method Bio[]    findAll()
 * @method Bio[]    findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 */
class BioRepository extends ServiceEntityRepository
{
    public function __construct(Finders $finders)
    {
        parent::__construct($finders, Bio::class);
    }

    /** The biography of one length in $locale, else in the fallback language. */
    public function findOneFor(BioLength $length, string $locale, string $fallback = 'en'): ?Bio
    {
        $locale = mb_strtolower(substr($locale, 0, 2));

        return $this->findOneBy(['length' => $length, 'locale' => $locale])
            ?? ($locale !== $fallback ? $this->findOneBy(['length' => $length, 'locale' => $fallback]) : null);
    }

    /**
     * The biographies of a page, by length, each in $locale or else in the
     * fallback language; a length nobody wrote is left out.
     *
     * @param list<string> $lengths the lengths wanted, in order ('short', 'medium', 'long')
     *
     * @return array<string, Bio> length => biography
     */
    public function findByLength(string $locale, array $lengths = ['short', 'medium', 'long'], string $fallback = 'en'): array
    {
        $bios = [];
        foreach ($lengths as $length) {
            $length = BioLength::tryFrom($length);
            if ($length && $bio = $this->findOneFor($length, $locale, $fallback)) {
                $bios[$length->value] = $bio;
            }
        }

        return $bios;
    }
}
