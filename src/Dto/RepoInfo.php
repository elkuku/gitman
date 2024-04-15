<?php

namespace App\Dto;

use App\Type\RepoInfoFile;

class RepoInfo
{
    public string $path;
    public bool $isValid = true;
    public string $branchInfo;

    public bool $hasFileChanges = false;
    public bool $hasCommitChanges = false;

    public int $added = 0;
    public int $modified = 0;
    public int $deleted = 0;
    public int $untracked = 0;

    public int $commitsBehind = 0;
    public int $commitsAhead = 0;

    /**
     * @var RepoInfoFile[]
     */
    public array $changedFiles = [];

    public string $debugOutput;
}