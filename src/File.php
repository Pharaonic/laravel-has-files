<?php

namespace Pharaonic\Laravel\Files;

use Illuminate\Database\Eloquent\Model;
use Pharaonic\Laravel\Uploader\Upload;

/**
 * File Model
 *
 * @version 2.0
 * @author Moamen Eltouny (Raggi) <raggi@raggitech.com>
 */
class File extends Model
{

    /**
     * Fillable Columns
     *
     * @var array
     */
    protected $fillable = ['field', 'upload_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function file()
    {
        return $this->belongsTo(Upload::class, 'upload_id');
    }

    /**
     * Get the owning model.
     */
    public function model()
    {
        return $this->morphTo();
    }
}
