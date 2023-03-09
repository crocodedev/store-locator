<?php

namespace App\Repositories;

class Import
{
    public static function csv($nameFile, $data = [])
    {
        $fp = fopen(public_path() . "/api/import/$nameFile.csv", 'wb');
        $data = $data->toArray();
        fputcsv($fp, array_keys($data[0]), ';');
        foreach ($data as $line) {
            fputcsv($fp, $line, ';');
        }
        fclose($fp);
    }
}
