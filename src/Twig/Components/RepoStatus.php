<?php

namespace App\Twig\Components;

use App\Dto\RepoInfo;
use Symfony\Component\Process\Process;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class RepoStatus
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public string $path = '';

    public function getInfo(): RepoInfo
    {
        if (!$this->path) {
            return new RepoInfo();
        }
        $process = new Process([
            'git',
            'status',
            '--porcelain',
        ], $this->path);
        $info = new RepoInfo();

        $info->path = $this->path;

        $process->run();

        if ($process->isSuccessful()) {
            $output = $process->getOutput();
            $info->modified = 66;
            $info->debugOutput = $process->getOutput();
        } else {
            $info->isValid = false;
        }


        return $info;
    }
}