<?php

namespace App\Service;

use App\Entity\Category;
use App\Entity\Revision;
use App\Entity\Media;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class RevisionService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createRevisionWithMedia(?string $url, ?Media $media, ?Category $category): Revision
    {
        $this->entityManager->beginTransaction();

        try {
            $revision = new Revision();
            $revision->setUrl($url);
            $revision->setCategory($category);

            if ($media) {
                $this->entityManager->persist($media);
                $revision->setMedia($media);
            }

            $this->entityManager->persist($revision);
            $this->entityManager->flush();

            $this->entityManager->commit();

            return $revision;
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            throw $e;
        }
    }
}
