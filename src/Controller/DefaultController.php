<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/', name: 'app_default', methods: ['GET'])]
class DefaultController extends AbstractController
{
    public function __invoke(
        #[Autowire('%env(APP_REPOSITORIES)%')] string $appRepositories,
    ): Response
    {
        $repositories = [];

        if (null === (new ExecutableFinder())->find('git')) {
            $this->addFlash('danger', 'The git executable has not been found on your system :(');
        }

        if (!$appRepositories) {
            $this->addFlash('warning', 'Please define your repositories in .local.env with APP_REPOSITORIES');
        } else {
            $repos = explode(':', $appRepositories);

            foreach ($repos as $repo) {
                $repositories[$repo] = (new Finder())
                    ->directories()
                    ->in($repo)
                    ->depth('== 0')
                    ->sortByName();
            }
        }

        return $this->render('default/index.html.twig', [
            'repositories' => $repositories,
        ]);
    }
}
