<?php

namespace App\Service;

use App\Dto\RepoInfo;
use Symfony\Component\Process\Process;

class GitService
{
    public function repoInfo(string $path): RepoInfo
    {
        if (!$path) {
            return new RepoInfo();
        }
        $process = new Process([
            'git',
            'status',
            '--branch',
            '--porcelain',
        ], $path);
        $info = new RepoInfo();

        $info->path = $path;

        $process->run();

        if ($process->isSuccessful()) {
            $debugOutput = $process->getOutput();
            $output = $process->getOutput();
            if ($output) {
                $lines = explode("\n", $output);
                dump($lines);
                $branchInfo = array_shift($lines);
                if (strpos($branchInfo, '[')) {
                    $info->hasCommitChanges = true;
                }

                $info->hasFileChanges = count($lines) > 1;

                $info->modified = 66;
                $info->debugOutput = $debugOutput;
            }
        } else {
            $info->isValid = false;
        }

        return $info;
    }
}