<?php

namespace NINA\Core\Contracts\Filesystem;

interface FilesystemFactory
{
    public function disk($name = null);
}