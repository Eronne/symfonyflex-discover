<?php
/**
 * @author Erwann LETUE <erwann.letue@gmail.com>
 * Date: 19/09/2017 at 16:21
 */

namespace App\Controller;

use App\Entity\News;
use App\Form\NewsType;
use App\Http\ViewControllerTrait;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class NewsController
 * @package App\Controller
 * @Route("/news")
 */
class NewsController
{
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
     * NewsController constructor.
     * @param \Twig_Environment $twig
     * @param EntityManager $entityManager
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(\Twig_Environment $twig, EntityManager $entityManager, FormFactoryInterface $formFactory)
    {
        $this->twig = $twig;
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
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
     * @return Response
     * @Route("/create", name="create_news")
     */
    public function createAction(Request $request): Response
    {
        $form = $this->formFactory->create(NewsType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $this->entityManager->persist($form->getData());
            $this->entityManager->flush();
        }

        return $this->render('News\create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}