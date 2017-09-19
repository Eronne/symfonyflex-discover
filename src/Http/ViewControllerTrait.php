<?php
/**
 * User: Erwann
 * Date: 19/09/2017
 * Time: 12:13
 */

namespace App\Http;

use Symfony\Component\HttpFoundation\Response;

trait ViewControllerTrait
{
    protected function render(string $templateName, array $parameters = [], int $statusCode = 200): Response
    {
        $response = new Response();
        $response->setContent($this->twig->render($templateName, $parameters));

        if (200 != $statusCode){
            $response->setStatusCode($statusCode);
        }

        return $response;
    }
}