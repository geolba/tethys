<?php

namespace App\Models;

use App\Dataset;
use App\Models\HashValue;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table = 'document_files';
    public $timestamps = true;

    protected $fillable = ['path_name', 'file_size', 'mime_type', 'label', 'sort_order'];

    public function dataset()
    {
        return $this->belongsTo(Dataset::class, 'document_id', 'id');
    }

    public function hashvalues()
    {
        return $this->hasMany(HashValue::class, 'file_id', 'id');
    }

     /**
     * Create hash value model objects from original file.
     *
     * TODO throws Exception in case hash computation is not possible
     *      (e.g., if referenced file is missing in file system)
     *
     * @return void
     */
    public function createHashValues()
    {
        $hashtypes = array('md5', 'sha512');
        
        foreach ($hashtypes as $type) {
            $hash = new HashValue();
            $hash->type = $type;
            $hashString = $this->getRealHash($type);
            $hash->value = $hashString;
            $this->hashvalues()->save($hash);
        }
    }

     /**
     * Get the hash value of the file
     *
     * @param string $type Type of the hash value, @see hash_file();
     * @return string hash value
     */
    private function getRealHash($type)
    {
        $hash = @hash_file($type, $this->getPath());
        if (empty($hash)) {
            throw new \Exception("Empty HASH for file '" . $this->getPath() . "'");
        }
        return $hash;
    }

    /**
     * Get full path of destination file.
     */
    private function getPath()
    {
        return  storage_path('app/public/' . $this->path_name);
    }
}
