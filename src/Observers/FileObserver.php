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
        $attribute = $file->caller->getAttributables()[$file->field];

        if ($attribute->getOriginal() === $file) {
            $attribute->reset($attribute->getValue());
        } else {
            $attribute->reset(null);
        }

        $file->caller->setRelation(
            'files',
            $file->caller
                ->files
                ->filter(fn($f) => $f->field != $file->field)
        );

        $file->upload->delete();
    }
}
