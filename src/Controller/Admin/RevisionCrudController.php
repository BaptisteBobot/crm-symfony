<?php

namespace App\Controller\Admin;

use App\Entity\Revision;
use App\Service\RevisionService;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use Exception;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\RequestStack;

class RevisionCrudController extends AbstractCrudController
{
    private $requestStack;
    private $revisionService;

    public function __construct(RequestStack $requestStack, RevisionService $revisionService)
    {
        $this->requestStack = $requestStack;
        $this->revisionService = $revisionService;
    }

    public static function getEntityFqcn(): string
    {
        return Revision::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('url'),
            AssociationField::new('media')->onlyOnIndex(),
            AssociationField::new('category'),
            TextField::new('mediaFile', 'Media File')->setFormType(FileType::class)->onlyOnForms()->setFormTypeOptions(['mapped' => false])
        ];
    }

    /**
     * @throws Exception
     */
    public function createEntity(string $entityFqcn)
    {
        $request = $this->requestStack->getCurrentRequest();
        $url = $request->request->get('url');
        $mediaFile = $request->files->get('mediaFile');
        $category = $request->request->get('category');

        return $this->revisionService->createRevisionWithMedia($url, $mediaFile, $category);
    }
}
