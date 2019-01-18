<?php

namespace Pat\JsonUpload;

use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;


/* The class manages the file management, creating, updating , deleting and downloading */
/* It uses eloquent json attributes and Laravel Storage */
/* Avoid the creating of tables to store files on any entity */




class UploadJson
{
    /*  Persis Method allows to save the file in the storage by creating opr updating simultaneos  */
    /*  the JSON field on the Database */
    /*  Params whill be what to save $files, and where to save $storage, and path */

    public function persist($model, $field, $disk, $path)
    {

        $files = request()->file($field);

        $uploads = collect([]);

        foreach ($files as $index => $file) {
            $file->storeAs($path, $file->getClientOriginalName(), $disk);

            $meta = ['id' => $index, 'name' => $file->getClientOriginalName(), 'mime' => $file->getMimeType(), 'path' => $path, 'disk' => $disk];
            $uploads->push($meta);

        }
        $model->$field = $uploads;
        $model->save();

    }

    /* Downloads the file from the storage, using the json record */
    /* Params */
    /* Because files are stored in a field of a table using json atributes */
    /* We need to get the name of the Model with the property or field where is located, an finally the index to download */
    public function download($entity, $field, $id, $index = 0)
    {
        try {

            //format class name  to match degfault laravel eloquent model namespace
            $model = "App\\".ucfirst($entity);
          
            //check is class 'model' exists
            if (!class_exists($model))
                return ['msg' => 'Model not found...'];

            //find the model
            $model = $model::find($id);

            //Check if files exists
            if (count($model->$field) == 0)
                return ['msg' => 'No Files available...'];

           //Get the file
            $file = collect($model->$field)->where('id', $index)->first();

            // check if file exists and is not null
            if(!isset($file))
                return ['msg' => 'File not found, please check the index.'];

            // Download the file from the storage
            return Storage::disk($file['disk'])->download($file['path'].'/'.$file['name']);


        } catch (\Exception $ex) {
            return ['msg' => $ex->getMessage()];

        }


    }
}