<?php


namespace App\Services;

use App\Entity\Language;
use App\Entity\Translation;
use Doctrine\ORM\EntityManagerInterface;

class LanguageManagement
{
    /**
     * @var EntityManagerInterface
     */
    private $ems;

    public function __construct(EntityManagerInterface $ems)
    {
        $this->ems = $ems;
    }

    public function getAll()
    {
        $languesAll =$this->ems->getRepository(Language::class)->findAll();
        return $languesAll;
    }

    /**
     * This will suppress UnusedLocalVariable
     * warnings in this method
     *
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function generateTrans():void
    {
        $nombreLangue = $this->ems->getRepository(Language::class)->findAll();
        foreach ($nombreLangue as $langue) {
            $nomFile = '../translations/messages.' . $langue->getCode() . ".xlf";

            $transword = $this->ems->getRepository(Translation::class)->findBy(['language' => $langue->getId()]);

            $f = fopen($nomFile, "w+");
            if ($f != false) {
                fputs($f, '<?xml version="1.0"?>
<xliff version="1.2" xmlns="urn:oasis:names:tc:xliff:document:1.2">
    <file source-language="' . $langue->getCode() . '" datatype="plaintext" original="file.ext">
        <body>');
                foreach ($transword as $key => $value) {
                    $keytext = $value->getKeytext() ;
                    $text = $value->getText();
                    fputs($f, '<trans-unit id="' . uniqid() . '">
                <source>' . strval($keytext). '</source>
                <target>' . strval($text) . '</target>
            </trans-unit>');
                }
                fputs($f, '</body>
    </file>
</xliff>');

                fclose($f);
            }
        }
    }
}
