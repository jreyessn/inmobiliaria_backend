<?php

namespace App\Repositories\Images;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Images\ImageRepository;
use App\Models\Images\Image;
use App\Validators\Images\ImageValidator;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

/**
 * Class ImageRepositoryEloquent.
 *
 * @package namespace App\Repositories\Images;
 */
class ImageRepositoryEloquent extends BaseRepository implements ImageRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Image::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * Guarda una imagen
     * 
     * @param object $file Archivo
     * @param object $model Instancia del modelo
     * @param array $options[type] Etiqueta para diferenciar de otras imagenes con la misma tabla
     * @param array $options[path] Ruta de almacenamiento
     */
    public function save(object $file, $model, array $options = [])
    {

        $type = $options["type"] ?? "Gallery";
        $path = $options["path"] ?? "gallery";

        $fileStore = $this->create([
            "model_type" => get_class($model),
            "model_id"   => $model->id,
            "type"       => $type
        ]);

        $storage = Storage::disk('local')->putFileAs($path, $file, "{$fileStore->id}-{$file->hashName()}");

        $fileStore->original_name = $file->getClientOriginalName();
        $fileStore->name = $storage;
        $fileStore->save();
    }

    /**
     * Guarda una imagen que viene con formato base64
     * 
     * @param string $file Archivo
     * @param object $model Instancia del modelo
     * @param array $options[type] Etiqueta para diferenciar de otras imagenes con la misma tabla
     * @param array $options[path] Ruta de almacenamiento
     */
    public function saveBase64(string $file, $model, array $options = [])
    {

        $type = $options["type"] ?? "Gallery";
        $path = $options["path"] ?? "gallery";

        $fileStore = $this->create([
            "model_type" => get_class($model),
            "model_id"   => $model->id,
            "type"       => $type
        ]);

        $file_name = 'image_' . rand(0,100000) . '.png';
        $image = preg_replace('/data:image\/(.*?);base64,/','',$file);

        Storage::disk('local')->put("{$path}/{$fileStore->id}-{$file_name}", base64_decode($image));

        $fileStore->original_name = $file_name;
        $fileStore->name = "{$path}/{$fileStore->id}-{$file_name}";
        $fileStore->save();
    }

    /**
     * Guardar multiples imagenes
     * 
     * @param array $imagesFiles Lista de archivos de imagenes
     * @param object $model Instancia del modelo
     * @param array $options[type] Etiqueta para diferenciar de otras imagenes con la misma tabla
     * @param array $options[path] Ruta de almacenamiento
     */
    public function saveMany(array $imagesFiles, $model, array $options = [])
    {
        foreach ($imagesFiles as $file) {
            $this->save($file, $model, $options);
        }
    }

    /**
     * Elimina las imagenes
     * 
     * @param object $model Instancia del modelo
     * @param array $options[type] Qué etiqueta se va a eliminar de la relación
     * @param array $options[name] Lista de elementos que se van a eliminar (array de nombres)
     */
    public function destroy($model, array $options = [])
    {
        $type  = $options["type"] ?? "Gallery";
        $names = $options["name"] ?? [];

        $records = $this->where([
            "model_type" => get_class($model),
            "model_id"   => $model->id,
            "type"       => $type
        ])->where(function($query) use ($names){
            if(count($names) > 0){
                $query->whereIn("name", $names);
            }
        })->get();

        foreach ($records as $record) {
            $record->delete();
        }

    }
    
}
