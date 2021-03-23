<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Service\Greeting;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class BlogController
{

    private $greeting;
    private $twig;

    public function __construct(Greeting $greeting, Environment $twig)
    {
        $this->greeting = $greeting;
        $this->twig = $twig;
    }

    /**
     * @Route("/{name}", name="blog_index")
     * @param Request $request request
     * @param string $name
     * @return Response
     */
    public function index(Request $request, string $name): Response
    {

        try {
            $html = $this->twig->render('base.html.twig', ['message' => $this->greeting->greet($name)]);
            return new Response($html);
        } catch (LoaderError | RuntimeError | SyntaxError $e) {
            return new Response('',500);
        }
    }
}