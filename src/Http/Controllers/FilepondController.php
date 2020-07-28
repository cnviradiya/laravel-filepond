<?php

namespace cnviradiya\LaravelFilepond\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use cnviradiya\LaravelFilepond\Filepond;

class FilepondController extends BaseController
{

    /**
     * @var Filepond
     */
    private $filepond;

    public function __construct(Filepond $filepond)
    {
        $this->filepond = $filepond;
    }

    /**
     * Uploads the file to the temporary directory
     * and returns an encrypted path to the file
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function uploadFile(Request $request)
    {
        $allFields = array_keys($request->all());
        $fieldName = config('filepond.input_name');
        if (is_array($fieldName) && is_array($allFields) && !empty($allFields[0]) && in_array($allFields[0], $fieldName)) {
            $fieldName = $allFields[0];
        }
        $input = $request->file($fieldName);
        $file = is_array($input) ? $input[0] : $input;

        if($input === null){
            return Response::make(config('filepond.input_name'). ' is required', 422, [
                'Content-Type' => 'text/plain',
            ]);
        }

        $tempPath = config('filepond.temporary_files_path');

        $filePath = @tempnam($tempPath, 'laravel-filepond');
        $filePath .= '.' . $file->extension();

        $filePathParts = pathinfo($filePath);

        if (!$file->move($filePathParts['dirname'], $filePathParts['basename'])) {
            return Response::make('Could not save file', 500, [
                'Content-Type' => 'text/plain',
            ]);
        }

        return Response::make($this->filepond->getServerIdFromPath($filePath), 200, [
            'Content-Type' => 'text/plain',
        ]);
    }

    /**
     * Takes the given encrypted filepath and deletes
     * it if it hasn't been tampered with
     *
     * @param Request $request
     * @return mixed
     */
    public function delete(Request $request)
    {
        $filePath = $this->filepond->getPathFromServerId($request->getContent());
        if(unlink($filePath)) {
            return Response::make('', 200, [
                'Content-Type' => 'text/plain',
            ]);
        }

        return Response::make('', 500, [
            'Content-Type' => 'text/plain',
        ]);
    }
}
