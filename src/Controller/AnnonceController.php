<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Entity\Annonce;
use App\Form\AnnonceType;
use App\Entity\AnnonceSearch;
use App\Form\AnnonceSearchType;
use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AnnonceController extends AbstractController
{
    /**
     * @Route("/annonce", name="annonce")
     */
    public function index(Request $request, PaginatorInterface $paginator, AnnonceRepository $annonceRepository)
    {
        // les annonces paginé
        $ducks = $paginator->paginate(
            $annonceRepository->findAllNotSoldQuery(),
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('annonce/index.html.twig', [
            'ducks' => $ducks
        ]);
    }

    /**
     * @Route("/annonce/search")
     */
    public function search(Request $request, AnnonceRepository $annonceRepository)
    {
        $annonceSearch = new AnnonceSearch();
        $form = $this->createForm(AnnonceSearchType::class, $annonceSearch);
        
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $annonces = $annonceRepository->search($annonceSearch);
            dump($annonces);
        }

        return $this->render('annonce/search-form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/annonce/new", methods={"POST", "GET"})
     * @IsGranted("ROLE_USER")
     */
    public function new(Request $request, EntityManagerInterface $em)
    {        
        $duck = new Annonce();
        $duck->addTag(new Tag());
        $newForm = $this->createForm(AnnonceType::class, $duck);
        $newForm->handleRequest($request);

        if ($newForm->isSubmitted() && $newForm->isValid()) {
            $duck->setUser($this->getUser());
            $em->persist($duck);
            $em->flush();

            return $this->redirectToRoute('app_annonce_show', ['id' => $duck->getId()]);
        }
        
        return $this->render('annonce/new.html.twig', [
            'duck' => $duck,
            'form' => $newForm->createView()
        ]);
    }

    /**
     * @Route("/annonce/{id}/edit"), methods={"POST", "GET"})
     * @Security("(is_granted('ROLE_USER') and duck.getUser() == user) or is_granted('ROLE_ADMIN')")
     */
    public function edit(Annonce $duck, EntityManagerInterface $em, Request $request)
    {
        $form = $this->createForm(AnnonceType::class, $duck);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('app_annonce_show', ['id' => $duck->getId()]);         
        }
        
        return $this->render('annonce/edit.html.twig', [
            'duck' => $duck,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/annonce/{id}", methods="DELETE")
     * @Security("(is_granted('ROLE_USER') and duck.getUser() == user) or is_granted('ROLE_ADMIN')")
     */
    public function delete(Annonce $duck, EntityManagerInterface $em, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $duck->getId(), $request->get('_token'))) {
            $em->remove($duck);
            $em->flush();
        }
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_admin_annonce_index');
        }
        return $this->redirectToRoute('app_profile_annonce');
    }

    /**
     * @Route("/annonce/{id}",
     * requirements={"id": "\d+"})
     * @return Response
     */
    public function show(int $id, AnnonceRepository $annonceRepository): Response
    {

        $ducks = $annonceRepository->find($id);

        if (!$ducks) {
            throw $this->createNotFoundException();
        }
        return $this->render('annonce/show.html.twig', [
            'ducks' => $ducks,
        ]);
    }

    /**
     * @Route(
     *  "/annonce/{slug}-{id}", 
     *  requirements={"slug": "[a-z0-9\-]*", "id": "\d+"}
     * )
     * @return Response
     */
    public function showBySlug(string $slug, int $id, AnnonceRepository $annonceRepository): Response
    {
        $ducks = $annonceRepository->findOneBy([
            'slug' => $slug,
            'id' => $id
        ]);

        if (!$ducks) {
            return $this->createNotFoundException();
        }

        return $this->render('annonce/show.html.twig', [
            'ducks' => $ducks,
        ]);
    }

    /**
     * @Route("/annonce/filter", methods={"GET"})
     */
    public function filter(Request $rq)
    {
        $repository = $this->getDoctrine()->getRepository(Annonce::class);

        $filters = [
            'betterThan' => $rq->query->getInt('better_than'),
            'newerThan' => $rq->query->get('newer_than')
        ];
        
        /**
         * @var AnnonceRepository $repository
         */
        
        $ducks = $repository->fetch($filters);

        if(!$ducks){
            throw $this->createNotFoundException("Nothing found...");
        }
        return $this->render('annonce/filter.html.twig', [
            'ducks' => $ducks
        ]);
    }

    /**
     * @Route("/annonce-by-tag/{id<\d+>}")
     */
    public function annonceByTag(Tag $tag){
        return $this->render('annonce/index.html.twig', [
            'ducks' => $tag->getAnnonces(),
            'selectedTagID' => $tag->getId()
        ]);
    }

}