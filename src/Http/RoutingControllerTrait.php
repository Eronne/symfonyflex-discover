<?php
/**
 * @author Erwann LETUE <erwann.letue@gmail.com>
 * Date: 20/09/2017 at 14:31
 */

namespace App\Http;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;


/**
 * Trait RoutingControllerTrait
 * @package App\Http
 * @property RouterInterface $router
 */
trait RoutingControllerTrait
{

    /**
     * @param string $routeName
     * @param array $parameters
     * @return RedirectResponse
     */
    protected function redirectToRoute(
        string $routeName,
        array $parameters = []
    ): RedirectResponse
    {
        return new RedirectResponse($this->generateurUrl($routeName, $parameters));
    }


    /**
     * @param string $routeName
     * @param array $parameters
     * @param int $isAbsolute
     * @return string
     */
    protected function generateurUrl(
        string $routeName,
        array $parameters = [],
        int $isAbsolute = RouterInterface::RELATIVE_PATH
    ): string
    {
        return $this->router->generate($routeName, $parameters, $isAbsolute);
    }
}