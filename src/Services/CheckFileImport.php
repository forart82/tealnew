<?php

namespace App\Services;

class CheckFileImport
{
    private $errors;


    public function checkList(string $filename): array
    {
        $this->errors = [];
        $filepath = "";
        $filepath = $filename;
        $handle = fopen($filepath, "r");
        $this->errors = $this->checkListColumns($handle);
        $handle = fopen($filepath, "r");
        $this->errors = $this->checkNames($handle);
        $handle = fopen($filepath, "r");
        $this->errors = $this->checkEmail($handle);
        dd($handle, $this->errors);
        return $this->errors;
    }

    public function checkListColumns($handle): array
    {
        $statmants = ["Firstname", "Lastname", "Email"];
        $data = [];
        $countElements = 0;
        $num = 0;
        $row = 0;
        if ($handle !== false) {
            $data = fgetcsv($handle, 1000, ",");
            while ($data !== false) {
                $num = count($data);
                for ($c = 0; $c < $num; $c++) {
                    if (0 == $row && 3 > $c) {
                        if ($data[$c] != $statmants[$c]) {
                            $this->errors[] = $statmants[$c] . ' is not the same!';
                        }
                    }
                    $countElements++;
                }
                $row++;
                $data = fgetcsv($handle, 1000, ",");
            }
            fclose($handle);
        }
        if ($countElements / $num != $row && $num != 3) {
            $this->errors[] = "Number of elements is not good!";
        }
        return $this->errors;
    }
    public function checkNames($handle): array
    {
        if ($handle !== false) {
            $row = 0;
            $data = fgetcsv($handle, 1000,",");
            while ($data !== false) {
                $num = count($data);
                if ($row != 0) {
                    for ($i = 0; $i < $num; $i++) {
                        if ($i != 2) {
                            if (!ctype_alpha($data[$i])) {
                                $this->errors[] = $data[$i] . ' is not alphabetic!';
                            }
                        }
                    }
                }
                $row++;
                $data = fgetcsv($handle, 1000, ",");
            }
            fclose($handle);
        }
        return $this->errors;
    }

    public function checkEmail($handle)
    {
        $faitRien = [];
        $num = 0;
        if ($handle !== false) {
            $row = 0;
            $data = fgetcsv($handle, 1000, ",");
            while ($data !== false) {
                $num = 0;
                foreach ($data as $d) {
                    $faitRien = $d;
                    $num++;
                }
                if ($row != 0) {
                    for ($i = 0; $i < $num; $i++) {
                        if ($i == 2) {
                            if (!filter_var($data[$i], FILTER_VALIDATE_EMAIL)) {
                                $this->errors[] = $data[$i] . ' is not a email-addresse!';
                            }
                        }
                    }
                }
                $row++;
                $data = fgetcsv($handle, 1000, ",");
            }
            fclose($handle);
        }
        return $this->errors;
    }

    public function getCsv(string $uploadDirectory, string $filename): array
    {
        $filepath = "";
        $filepath = $uploadDirectory . '/' . $filename;
        $arrayOfCsv = [];
        $handle = fopen($filepath, "r");
        if ($handle !== false) {
            $data = fgetcsv($handle, 1000, ",");
            while ($data !== false) {
                $num = count($data);
                $arrayOfCsv[] = $data;
                for ($c = 0; $c < $num; $c++) {
                }
                $data = fgetcsv($handle, 1000, ",");
            }
            fclose($handle);
        }
        return $arrayOfCsv;
    }
}
