<?php

namespace App\Controller;

use App\Service\GitService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/filediff', name: 'app_filediff', methods: ['GET'])]
class FileDiffController extends AbstractController
{
    public function __invoke(
        GitService $gitService,
        Request    $request,
    ): Response
    {
        $repo = $request->get('repo');
        $file = $request->get('file');
        $response = new \stdClass();
        try {
            if ($repo && $file) {
                $response->text = $gitService->getFileDiff($repo, $file);
            }
        } catch (\RuntimeException $exception) {
            $response->error = $exception->getMessage();
        }
        return $this->json($response);
    }
}
