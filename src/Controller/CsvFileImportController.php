<?php

namespace App\Controller;

use App\Services\CsvFileImport;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/import")
 */
class CsvFileImportController extends AbstractController
{


    private $csvFileImport;

    public function __construct(
        CsvFileImport $csvFileImport
    ) {
        $this->csvFileImport=$csvFileImport;
    }

    /**
     * @Route("/csv", name="import_csv")
     */
    public function importCsv()
    {
        if (!empty($_FILES)) {
            $targetDir = $this->getParameter("uploads_directory");
            $targetFile = $targetDir . '/' . uniqid() . '.csv';
            move_uploaded_file($_FILES["csvFileImport"]["tmp_name"], $targetFile);
            $this->csvFileImport->doCsv($targetFile,$this->getUser());
            unlink($targetFile);
            return $this->render('csvfileimport/import.html.twig', [
                'errorTable' => $this->csvFileImport->getErrorTable(),
            ]);
        }
        return $this->render('csvfileimport/import.html.twig', [
            'errorTable' => null,
        ]);
    }

    /**
     * @Route("/table", name="import_table")
     */
    public function importTable(Request $request)
    {
        $rowAsString = "";
        $table = $request->request->all();
        $tableValues = [];
        $targetFile = "file.csv";+
        $handle = fopen($targetFile, 'w');

        if ($table) {
            $tableValues = array_unique(preg_filter('/[0-9]+/', '', array_keys($table)));
            fputcsv($handle, $tableValues, ';');
            for ($i = 0; $i < (count($table) / count($tableValues)); $i++) {
                $rowAsString = "";

                foreach ($tableValues as $value) {
                    $rowAsString .= $table[$value . '' . $i] . ';';
                }
                fputcsv($handle, [substr($rowAsString, 0, -1)]);
            }
            fclose($handle);
            $this->csvFileImport->doCsv($targetFile, $this->getUser());

            unlink($targetFile);

            return $this->render('csvfileimport/import.html.twig', [
                'errorTable' => $this->csvFileImport->getErrorTable($targetFile,$this->getUser()),
            ]);
        }
        return $this->render('admin/admin.html.twig', []);
    }
}
