<?php

namespace App\Controller;

use App\Dto\RepoInfo;
use App\Service\GitService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/repoinfo', name: 'app_repoinfo', methods: ['GET'])]
class RepoInfoController extends AbstractController
{
    public function __invoke(
        GitService $gitService,
        Request    $request,
    ): Response
    {
        $path = $request->get('path');

        if (!$path) {
            $info = new RepoInfo();
        } else {
            $info = $gitService->repoInfo($path);
        }

        return $this->json($info);
    }
}
