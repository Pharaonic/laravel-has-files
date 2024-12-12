<?php

namespace Pharaonic\Laravel\Files\Traits;

use Pharaonic\Laravel\Files\Models\File;

trait FilesHandler
{
    /**
     * Files data & options list.
     *
     * @var array
     */
    protected $filesData = [];

    /**
     * Has thumbnail relationship
     *
     * @var boolean
     */
    protected $hasThumbnailRelationship = false;

    /**
     * Check if files relationship has been loaded.
     *
     * @var boolean
     */
    protected $filesRelationLoaded = false;

    /**
     * Check if file attribute
     * 
     * @param string $key
     * @return bool
     */
    public function isFileAttribute(string $key)
    {
        return in_array($key, $this->getFilesAttributes());
    }

    /**
     * Getting files attributes
     * 
     * @return array
     */
    public function getFilesAttributes(): array
    {
        return array_keys($this->filesData);
    }

    /**
     * Getting file options.
     * 
     * @param string $key
     * @return array
     */
    public function getFileOptions(string $key)
    {
        return $this->filesData[$key] ?? [];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function files()
    {
        return $this
            ->morphMany(File::class, 'model')
            ->with('upload')
            ->when(
                $this->hasThumbnailRelationship,
                fn($q) => $q->with('upload.thumbnail')
            );
    }

    /**
     * Assign the files to it's attributes.
     *
     * @return void
     */
    public function loadFiles()
    {
        if (!$this->filesRelationLoaded) {
            $this->files->each(function ($file) {
                $this->{$file->field} = $file->setCaller($this);
            });

            $this->filesRelationLoaded = true;
        }
    }

    /**
     * Getting a specific file.
     *
     * @param string $key
     * @return \Pharaonic\Laravel\Files\Models\File|null
     */
    public function getFile(string $key)
    {
        $this->loadFiles();

        if ($this->isFileAttribute($key)) {
            return $this->attributables[$key]?->getValue()
                ?? $this->files->where('field', $key)->first()
                ?? null;
        }

        return null;
    }

    /**
     * Get Dirty Files
     *
     * @return array
     */
    public function getDirtyFiles()
    {
        return array_filter(
            $this->getDirtyAttributables(),
            fn($attributable) => $this->isFileAttribute($attributable->getName())
        );
    }
}
