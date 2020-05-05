<?php

namespace App\Controller;


use App\Repository\CsvKeyValuesRepository;
use App\Repository\UserRepository;
use App\Repository\EmailsRepository;
use App\Services\CsvFileImport;
use App\Services\SendMailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/import")
 */
class CsvFileImportController extends AbstractController
{

    private $csvKeyValuesRepository;
    private $userRepository;
    private $userPasswordEncoderInterface;
    private $entityManagerInterface;
    private $request;
    /**
     * @var SendMailer
     */
    private $sendMailer;
    private $translatorInterface;

    public function __construct(
        CsvKeyValuesRepository $csvKeyValuesRepository,
        UserRepository $userRepository,
        UserPasswordEncoderInterface $userPasswordEncoderInterface,
        EntityManagerInterface $entityManagerInterface,
        RequestStack $requestStack,
        MailerInterface $mailerInterface,
        ContainerInterface $containerInterface = null,
        EmailsRepository $emailsRepository,
        TranslatorInterface $translatorInterface
    ) {
        $this->csvKeyValuesRepository = $csvKeyValuesRepository;
        $this->userRepository = $userRepository;
        $this->userPasswordEncoderInterface = $userPasswordEncoderInterface;
        $this->entityManagerInterface = $entityManagerInterface;
        $this->request = $requestStack->getCurrentRequest();
        $this->sendMailer = new SendMailer(
            $emailsRepository,
            $containerInterface,
            $mailerInterface
        );
        $this->translatorInterface=$translatorInterface;
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
            $csvfile = new CsvFileImport(
                $targetFile,
                $this->csvKeyValuesRepository,
                $this->userRepository,
                $this->getUser(),
                $this->userPasswordEncoderInterface,
                $this->entityManagerInterface,
                $this->sendMailer,
                $this->translatorInterface
            );
            $csvfile->doCsv();
            unlink($targetFile);
            return $this->render('csvfileimport/import.html.twig', [
                'errorTable' => $csvfile->getErrorTable(),
            ]);
        }
        return $this->render('csvfileimport/import.html.twig', [
            'errorTable' => null,
        ]);
    }

    /**
     * @Route("/table", name="import_table")
     */
    public function importTable()
    {
        $rowAsString = "";
        $table = $this->request->request->all();
        $tableValues = [];
        $targetFile = "file.csv";
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
            $csvfile = new CsvFileImport(
                $targetFile,
                $this->csvKeyValuesRepository,
                $this->userRepository,
                $this->getUser(),
                $this->userPasswordEncoderInterface,
                $this->entityManagerInterface,
                $this->sendMailer,
                $this->translatorInterface
            );
            $csvfile->doCsv();

            unlink($targetFile);

            return $this->render('csvfileimport/import.html.twig', [
                'errorTable' => $csvfile->getErrorTable(),
            ]);
        }
        return $this->render('admin/admin.html.twig', []);
    }
}
