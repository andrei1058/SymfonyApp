<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/** @Route ("/blog") */
class BlogController
{
    private $twig;
    private $session;
    private $router;

    public function __construct(Environment $twig, SessionInterface $session, RouterInterface $router)
    {
        $this->twig = $twig;
        $this->session = $session;
        $this->router = $router;
    }

    /**
     * @Route("/", name="blog_index")
     * @return Response
     */
    public function index(): Response
    {

        try {
            $html = $this->twig->render('blog/index.html.twig',
                [
                    'posts' => $this->session->get('posts')
                ]);
            return new Response($html);
        } catch (LoaderError | RuntimeError | SyntaxError $e) {
            return new Response('something went wrong ' . $e, 500);
        }
    }

    /** @Route("/add", name="blog_add") */
    public function add()
    {
        $posts = $this->session->get('posts');
        $id = uniqid();
        $posts[$id] = [
            'title' => 'A nice post title ' . rand(1, 500),
            'text' => 'Some random text nr ' . rand(1, 500),
            'date' => new \DateTime()
        ];
        $this->session->set('posts', $posts);
        return new RedirectResponse($this->router->generate('blog_index'));
    }

    /** @Route("/show/{id}", name="blog_show")
     * @param string $id
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function show(string $id): Response
    {
        $posts = $this->session->get('posts');
        if (!$posts || !isset($posts[$id])) {
            throw new NotFoundHttpException('Post not found');
        }

        $html = $this->twig->render('blog/post.html.twig',
            [
                'id' => $id,
                'post' => $posts[$id]
            ]);

        return new Response($html);
    }
}