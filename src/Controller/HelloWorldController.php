<?php
/**
 * User: Erwann
 * Date: 19/09/2017
 * Time: 10:22
 */

namespace App\Controller;
use App\Http\ViewControllerTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class HelloWorldController
 * @package App\Controller
 * @Route("/")
 */
class HelloWorldController
{
    use ViewControllerTrait;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * HelloWorldController constructor.
     * @param \Twig_Environment $twig
     */
    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }


    /**
     * @param Request $request
     * @param string $name
     * @return Response
     * @Route("/", name="hello_index")
     */
    public function indexAction(Request $request): Response
    {
        return $this->render('@App/HelloWorld/index.html.twig');
    }
}