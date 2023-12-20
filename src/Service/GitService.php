<?php

namespace App\Service;

use App\Dto\RepoInfo;
use App\Type\RepoInfoFile;
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
                $branchInfo = array_shift($lines);
                if (strpos($branchInfo, '[')) {
                    $info->hasCommitChanges = true;
                }

                $info->hasFileChanges = count($lines) > 1;

                foreach ($lines as $line) {
                    if (!$line) {
                        continue;
                    }
                    $file = new RepoInfoFile();
                    $file->path = substr($line, 2);

                    $status = substr($line, 0, 2);

                    switch ($status) {
                        case 'A ':
                            $info->added++;

                            $file->status = 'A';
                            break;
                        case ' M':
                            $info->modified++;

                            $file->status = 'M';
                            break;
                        case 'D ':
                            $info->deleted++;

                            $file->status = 'D';
                            break;

                        case '??':
                            $info->untracked++;

                            $file->status = '?';
                            break;

                        default:
                            $file->status = '-';
                            break;
                    }

                    $info->changedFiles[] = $file;
                }

                $info->debugOutput = $debugOutput;
            }
        } else {
            $info->isValid = false;
        }

        return $info;
    }

    public function getFileDiff(string $repo, string $file): string
    {
        $process = new Process([
            'git',
            'diff',
            $file,
        ], $repo);

        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException($process->getErrorOutput());
        }

        return $process->getOutput();
    }
}