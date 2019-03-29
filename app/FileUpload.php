<?php

namespace App;

class FileUpload {
    private $file;

    public function __construct($file) {
        $this->file = $file;
    }

    public function fileUpload() {
        try {
            $file_data = array();
            $name = time() . '.' . $this->file->getClientOriginalName();
            $folder = storage_path('images');
            $this->file->move($folder, $name);
            $absolute_path = $folder . '/' . $name;
            $file_data['name'] = $name;
            $file_data['folder'] = $folder;
            $file_data['absolute_path'] = $absolute_path;

            return $file_data;
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

}