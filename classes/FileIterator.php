<?php

class FileIterator extends FilterIterator
{
    private ?array $filter = null;

    public function __construct(FilesystemIterator $iterator, array $extensions = null)
    {
        parent::__construct($iterator);
        $this->filter = $extensions;
    }

    public function accept(): bool
    {
        $current = $this->current();
        return $current->isFile() && !$current->isLink() &&
            (!is_array($this->filter) || in_array($current->getExtension(), $this->filter));
    }
}