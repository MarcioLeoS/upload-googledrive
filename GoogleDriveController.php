<?php

namespace App\Http\Controllers;

use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;
use Google_Service_Exception;
use Exception;

class GoogleDriveController extends Controller
{
    public function uploadFile()
    {
        $client = new Google_Client();
        $client->setAuthConfig(config_path('cargararchivos-422219-8a5cb877ab'));
        $client->setScopes(['https://www.googleapis.com/auth/drive.file']);

        try {
            $service = new Google_Service_Drive($client);
            $file_path = storage_path('app/public/logo_text.png');

            $file = new Google_Service_Drive_DriveFile();
            $file->setName(basename($file_path));
            $file->setParents(["1BORAKh6Kx1uqJQcTGla4ywqoM98c-"]);
            $file->setDescription("Archivo cargado desde PHP");
            $file->setMimeType("image/png");

            $resultado = $service->files->create(
                $file,
                [
                    'data' => file_get_contents($file_path),
                    'mimeType' => "image/png",
                    'uploadType' => 'media'
                ]
            );

            return '<a href="https://drive.google.com/open?id=' . $resultado->id . '" 
            target="_blank">' . $resultado->name . '</a>';
        } catch (Google_Service_Exception $gs) {
            $message = json_decode($gs->getMessage());
            return $message->error->message;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
