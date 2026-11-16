<?php

namespace Base\Press\Repository;

use Base\Press\Entity\Photo;
use Base\Database\Repository\Finders;
use Base\Database\Repository\ServiceEntityRepository;

/**
 * @method Photo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Photo|null findOneBy(array $criteria, ?array $orderBy = null)
 * @method Photo[]    findAll()
 * @method Photo[]    findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 */
class PhotoRepository extends ServiceEntityRepository
{
    public function __construct(Finders $finders)
    {
        parent::__construct($finders, Photo::class);
    }

    /** @return list<Photo> the pictures shown, in their order */
    public function findVisible(?int $limit = null): array
    {
        return $this->findBy(['visible' => true], ['position' => 'ASC', 'id' => 'ASC'], $limit);
    }

    /** @return list<Photo> the pictures of the site's own gallery, in their order */
    public function findGallery(?int $limit = null): array
    {
        return $this->findBy(['gallery' => true], ['position' => 'ASC', 'id' => 'ASC'], $limit);
    }
}
