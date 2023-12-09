<?php

namespace App\Dto;

class RepoInfo
{
    public bool $isValid = true;
    public bool $hasFileChanges = false;
    public bool $hasCommitChanges = false;
    public string $path;

    public int $modified = 0;
    public string $branchInfo;

    public string $debugOutput;
}