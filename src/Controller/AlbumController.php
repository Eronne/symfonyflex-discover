<?php
/**
 * Created by IntelliJ IDEA.
 * User: eletue
 * Date: 22/09/2017
 * Time: 13:54
 */

namespace App\Controller;

use App\Entity\Album;
use App\Entity\User;
use App\Form\AlbumType;
use App\Http\RoutingControllerTrait;
use App\Http\ViewControllerTrait;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class AlbumController
 * @package App\Controller
 * @Route("/albums")
 */
class AlbumController
{
    use RoutingControllerTrait;
    use ViewControllerTrait;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var FormFactoryInterface
     */
    private  $formFactory;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var FlashBag
     */
    private $flashbag;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * AlbumController constructor.
     * @param \Twig_Environment $twig
     * @param EntityManager $entityManager
     * @param FormFactoryInterface $formFactory
     * @param RouterInterface $router
     * @param TokenStorageInterface $tokenStorage
     * @param FlashBag $flashBag
     */
    public function __construct(
        \Twig_Environment $twig,
        EntityManager $entityManager,
        FormFactoryInterface $formFactory,
        RouterInterface $router,
        TokenStorageInterface $tokenStorage,
        FlashBag $flashBag)
    {
        $this->twig = $twig;
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->tokenStorage = $tokenStorage;
        $this->flashbag = $flashBag;
    }


    /**
     * @param Request $request
     * @return Response
     * @Route("/", name="albums_list")
     */
    public function listAction(Request $request): Response
    {
        $repository = $this->entityManager->getRepository(Album::class);
        $albumList = $repository->findAllOrderByCreatedDesc();

        return $this->render('@App\Album\index.html.twig', [
            "albumList" => $albumList
        ]);
    }


    /**
     * @param Request $request
     * @param int|null $albumId
     * @return Response
     * @Route("/show/{albumId}", name="show_albums")
     */
    public function showAction(Request $request, ?int $albumId): Response
    {
        $album = $this->entityManager->getRepository(Album::class)->find($albumId);

        return $this->render('@App\Album\show.html.twig', [
            'album' => $album
        ]);
    }


    /**
     * @param Request $request
     * @param int|null $albumId
     * @return Response
     * @internal param int|null $ablumId
     * @Route("/create", name="create_albums")
     * @Route("/edit/{albumId}", name="edit_albums")
     */
    public function createOrEditAction(Request $request, ?int $albumId = null): Response
    {
        $album = new Album();

        if (null != $albumId){
            $album = $this->retrieveAlbum($albumId);
        }

        /** @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();
        $album->setAuthor($user);

        $form = $this->formFactory->create(AlbumType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $this->entityManager->persist($form->getData());
            $this->entityManager->flush();

            if (null != $albumId){
                $this->flashbag->add('success', 'The album has been edited');
            } else {
                $this->flashbag->add('success', 'The album has been created');
            }

            return $this->redirectToRoute('albums_list');
        }

        return $this->render('@App\Album\actions.html.twig', [
            'form' => $form->createView(),
            'isEditable' => null != $albumId
        ]);
    }


    /**
     * @param Request $request
     * @param int $albumId
     * @return Response
     * @Route("/delete/{albumId}", name="delete_albums")
     */
    public function deleteAction(Request $request, int $albumId): Response
    {
        $album = $this->retrieveAlbum($albumId);
        $this->entityManager->remove($album);
        $this->entityManager->flush();
        $this->flashbag->add('success', 'The album has been deleted');
        return $this->redirectToRoute('albums_list');
    }


    /**
     * @param int $albumId
     * @return Album
     * @internal param int $album
     */
    private function retrieveAlbum(int $albumId): Album
    {
        $album = $this->entityManager->getRepository(Album::class)->findOneBy([
            'id' => $albumId
        ]);
        if ($album === null){
            throw new NotFoundHttpException(
                'The Album n° ' . $albumId . ' is not found'
            );
        }

        return $album;
    }
}