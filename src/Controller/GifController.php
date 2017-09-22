<?php
/**
 * Created by IntelliJ IDEA.
 * User: eletue
 * Date: 22/09/2017
 * Time: 16:44
 */

namespace App\Controller;

use App\Entity\Gif;
use App\Http\RoutingControllerTrait;
use App\Http\ViewControllerTrait;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\Response;
/**
 * Class GifController
 * @package App\Controller
 * @Route("/gifs")
 */
class GifController
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
    private $formFactory;

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
     * @param int|null $gifId
     * @return Response
     * @internal param int|null $ablumId
     * @Route("/create", name="create_gifs")
     * @Route("/edit/{gifId}", name="edit_gifs")
     */
    public function createOrEditAction(Request $request, ?int $gifId = null): Response
    {
        $gif = new Gif();

        if (null != $gifId){
            $gif = $this->retrieveGif($gifId);
        }

        $form = $this->formFactory->create(Gif::class, $gif);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $this->entityManager->persist($form->getData());
            $this->entityManager->flush();

            if (null != $gifId){
                $this->flashbag->add('success', 'The gif has been edited');
            } else {
                $this->flashbag->add('success', 'The gif has been created');
            }

            return $this->redirectToRoute('albums_list');
        }

        return $this->render('@App\Album\actions.html.twig', [
            'form' => $form->createView(),
            'isEditable' => null != $gifId
        ]);
    }

    public function retrieveGif(int $gifId): Gif
    {
        $gif = $this->entityManager->getRepository(Gif::class)->findOneBy([
            'id' => $gifId
        ]);

        if ($gif === null){
            throw new NotFoundHttpException(
                'The Album n° ' . $gifId . ' is not found'
            );
        }

        return $gif;
    }
}