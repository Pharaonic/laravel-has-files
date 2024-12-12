<?php

namespace Pharaonic\Laravel\Files\Observers;

use Pharaonic\Laravel\Files\Models\File;

class FileObserver
{
    /**
     * Handle the model "deleting" event.
     *
     * @param  File $file
     * @return void
     */
    public function deleting(File $file)
    {
        $file->file->delete();
    } 
}
