<?php

namespace App\Models;

use App\Models\Dataset;
use App\Models\HashValue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class File extends Model
{
    use HasFactory;
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
        //return  storage_path('app/public/' . $this->path_name);
        return public_path('storage/' . $this->path_name);
    }

    public function exists()
    {
        return \Illuminate\Support\Facades\File::exists(public_path('storage/' . $this->path_name));
    }

    public function formatSize($precision = 1)
    {
        $size = $this->file_size;
        $unit = ['Byte', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB'];
        for ($i = 0; $size >= 1024 && $i < count($unit) - 1; $i++) {
            $size /= 1024;
        }
        return round($size, $precision) . ' ' . $unit[$i];
    }
}
