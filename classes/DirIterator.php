<?php

class DirIterator extends FilterIterator
{

    public function __construct(DirectoryIterator $iterator)
    {
        parent::__construct($iterator);
    }

    public function accept(): bool
    {
        return $this->current()->isDir() && !$this->current()->isLink();
    }
}