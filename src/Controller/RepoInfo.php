<?php

namespace App\Controller;

use App\Service\GitService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/repoinfo', name: 'app_repoinfo', methods: ['GET'])]
class RepoInfo extends BaseController
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
