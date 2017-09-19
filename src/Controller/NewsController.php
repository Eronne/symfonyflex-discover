<?php
/**
 * @author Erwann LETUE <erwann.letue@gmail.com>
 * Date: 19/09/2017 at 16:21
 */

namespace App\Controller;

use App\Http\ViewControllerTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
     * NewsController constructor.
     * @param \Twig_Environment $twig
     */
    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }


    /**
     * @param Request $request
     * @return Response
     * @Route("/", name="news_list")
     */
    public function listAction(Request $request): Response
    {
        return $this->render('@App/News/index.html.twig', []);
    }
}