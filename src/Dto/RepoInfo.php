<?php

namespace App\Dto;

class RepoInfo
{
    public bool $isValid = true;
    public bool $hasFileChanges = false;
    public bool $hasCommitChanges = false;
    public $path;

    public int $modified = 0;
    public string $branchInfo;

    public $debugOutput;
}