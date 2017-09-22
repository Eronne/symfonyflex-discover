<?php
/**
 * @author Erwann LETUE <erwann.letue@gmail.com>
 * Date: 19/09/2017 at 16:21
 */

namespace App\Controller;

use App\Entity\News;
use App\Entity\User;
use App\Form\NewsType;
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
 * Class NewsController
 * @package App\Controller
 * @Route("/news")
 */
class NewsController
{
    use ViewControllerTrait;
    use RoutingControllerTrait;

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
     * NewsController constructor.
     * @param \Twig_Environment $twig
     * @param EntityManager $entityManager
     * @param FormFactoryInterface $formFactory
     * @param RouterInterface $router
     * @param FlashBag $flashbag
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        \Twig_Environment $twig,
        EntityManager $entityManager,
        FormFactoryInterface $formFactory,
        RouterInterface $router,
        FlashBag $flashbag,
        TokenStorageInterface $tokenStorage)
    {
        $this->twig = $twig;
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->flashbag = $flashbag;
        $this->tokenStorage = $tokenStorage;
    }


    /**
     * @param Request $request
     * @return Response
     * @Route("/", name="news_list")
     */
    public function listAction(Request $request): Response
    {
        $repository = $this->entityManager->getRepository(News::class);
        $newsList = $repository->findAllOrderByCreatedDesc();

        return $this->render('@App\News\index.html.twig', [
            "newsList" => $newsList
        ]);
    }


    /**
     * @param Request $request
     * @param int|null $newsId
     * @return Response
     * @Route("/create", name="create_news")
     * @Route("/edit/{newsId}", name="edit_news")
     */
    public function createOrEditAction(Request $request, ?int $newsId = null): Response
    {
        $news = null;

        if (null != $newsId){
            $news = $this->retrieveNews($newsId);
        }

        $form = $this->formFactory->create(NewsType::class, $news);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            /** @var User $user */
            $user = $this->tokenStorage->getToken()->getUser();

            if (null == $newsId){
                $form->getData()->setTitle(
                    $form->getData()->getTitle() . ' - ' . $user->getUsername()
                );
            }

            $this->entityManager->persist($form->getData());
            $this->entityManager->flush();

            if (null != $newsId){
                $this->flashbag->add('success', 'The news has been edited');
            } else {
                $this->flashbag->add('success', 'The news has been created');
            }

            return $this->redirectToRoute('news_list');
        }

        return $this->render('News\create.html.twig', [
            'form' => $form->createView(),
            'isEditable' => null != $newsId
        ]);
    }


    /**
     * @param Request $request
     * @param int $newsId
     * @return Response
     * @Route("/delete/{newsId}", name="delete_news")
     */
    public function deleteAction(Request $request, int $newsId): Response
    {
        $news = $this->retrieveNews($newsId);
        $this->entityManager->remove($news);
        $this->entityManager->flush();
        $this->flashbag->add('success', 'The news has been deleted');
        return $this->redirectToRoute('news_list');
    }


    /**
     * @param int $newsId
     * @return News
     * @internal param int $news
     */
    private function retrieveNews(int $newsId): News
    {
        $news = $this->entityManager->getRepository(News::class)->findOneBy([
            'id' => $newsId
        ]);
        if ($news === null){
            throw new NotFoundHttpException(
                'The News n° ' . $newsId . ' is not found'
            );
        }

        return $news;
    }
}