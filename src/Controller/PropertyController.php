<?php
/**
 * Created by PhpStorm.
 * User: matthieu
 * Date: 08/11/18
 * Time: 16:06
 */

namespace App\Controller;


use App\Entity\Property;
use App\Entity\PropertySearch;
use App\Form\PropertySearchType;
use App\Repository\PropertyRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends AbstractController
{
    /**
     * @var PropertyRepository
     */
    private $repositoryProperty;

    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(PropertyRepository $repositoryProperty, ObjectManager $em)
    {
        $this->repositoryProperty = $repositoryProperty;
        $this->em = $em;
    }

    /**
     * @Route("/biens", name="properties")
     * @return Response
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $search = new PropertySearch();
        $form = $this->createForm(PropertySearchType::class, $search);
        $form->handleRequest($request);

        $properties = $paginator->paginate(
            $this->repositoryProperty->findAllVisibleQuery($search),
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('property/index.html.twig', [
            'current_menu' => 'properties',
            'properties' => $properties,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/biens/{slug}-{id}", name="property.show", requirements={"slug": "[a-z0-9\-]*"} )
     * @param Property $property
     * @param string $slug
     * @return Response
     */
    public function show(Property $property, string $slug): Response
    {
        if ($property->getSlug() !== $slug) {
            return $this->redirectToRoute('property.show', [
                   'id' => $property->getId(),
                   'slug' => $property->getSlug()
                ], 301
            );
        }

        return $this->render('property/show.html.twig', [
            'property' => $property,
            'current_menu' => 'properties'
        ]);
    }
}