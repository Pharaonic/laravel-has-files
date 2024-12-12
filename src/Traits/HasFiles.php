<?php

namespace Pharaonic\Laravel\Files\Traits;

use Pharaonic\Laravel\Assistant\Traits\Eloquent\Attributable;
use Pharaonic\Laravel\Files\Observers\ModelObserver;

trait HasFiles
{
    use Attributable, FilesHandler;

    /**
     * Initialize Has Files
     * 
     * @return void
     */
    public function initializeHasFiles()
    {
        if (property_exists($this, 'files')) {
            foreach ($this->files as $key => $value) {
                $attribute = is_numeric($key) ? $value : $key;
                $options = is_numeric($key) ? [] : $value;

                $this->fillable[] = $attribute;
                $this->filesData[$attribute] = $options;

                $this->addAttributable(
                    $attribute,
                    null,
                    fn() => $this->getFile($attribute)
                );

                // Check Thumbnail Relationship Existence
                if (isset($options['thumbnail'])) {
                    $this->hasThumbnailRelationship = true;
                }
            }

            unset($this->files);
        }
    }

    /**
     * Boot the HasFiles Trait
     *
     * @return void
     */
    public static function bootHasFiles()
    {
        static::observe(ModelObserver::class);
    }
}
