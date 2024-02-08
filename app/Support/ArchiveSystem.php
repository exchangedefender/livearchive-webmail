<?php

namespace App\Support;

class ArchiveSystem
{
    public function __construct(
        protected ArchiveDatabase $database,
        protected ArchiveFileSystem $fileSystem,
    ) {

    }

    public function database(): ArchiveDatabase
    {
        return $this->database;
    }

    public function fileSystem(): ArchiveFileSystem
    {
        return $this->fileSystem;
    }
}
