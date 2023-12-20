<?php

namespace App\Dto;

use App\Type\RepoInfoFile;

class RepoInfo
{
    public bool $isValid = true;
    public bool $hasFileChanges = false;
    public bool $hasCommitChanges = false;
    public string $path;

    public int $added = 0;
    public int $modified = 0;
    public int $deleted = 0;
    public int $untracked = 0;

    public string $branchInfo;

    /**
     * @var RepoInfoFile[]
     */
    public array $changedFiles = [];

    public string $debugOutput;
}