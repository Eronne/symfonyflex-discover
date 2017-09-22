<?php
/**
 * Created by IntelliJ IDEA.
 * User: eletue
 * Date: 22/09/2017
 * Time: 13:54
 */

namespace App\Controller;

use App\Http\ViewControllerTrait;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;


/**
 * Class AlbumController
 * @package App\Controller
 * @Route("/albums")
 */
class AlbumController
{
    use ViewControllerTrait;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var EntityManager
     */
    private $entityManger;

    /**
     * @var FormFactoryInterface
     */
    private  $formFactory;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * AlbumController constructor.
     * @param \Twig_Environment $twig
     * @param EntityManager $entityManger
     * @param FormFactoryInterface $formFactory
     * @param RouterInterface $router
     */
    public function __construct(
        \Twig_Environment $twig,
        EntityManager $entityManger,
        FormFactoryInterface $formFactory,
        RouterInterface $router)
    {
        $this->twig = $twig;
        $this->entityManger = $entityManger;
        $this->formFactory = $formFactory;
        $this->router = $router;
    }


    /**
     * @param Request $request
     * @return Response
     * @Route("/", name="albums_list")
     */
    public function listAction(Request $request): Response
    {
        return $this->render('@App\Album\index.html.twig');
    }
}