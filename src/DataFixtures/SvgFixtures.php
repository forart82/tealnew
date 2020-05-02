<?php

namespace App\DataFixtures;

use App\Entity\Svg;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SvgFixtures extends Fixture implements OrderedFixtureInterface
{


    private $container;
    private $listOfFiles;

    public function __construct(ContainerInterface $containerInterface = null)
    {
        $this->container = $containerInterface;
        $this->listOfFiles = [];
    }

    public function load(ObjectManager $manager)
    {
        $dirs = $this->container->getParameter('svg_directory');
        $this->currentDirectory = "svg";

        $this->loadSvgFiles($dirs);
        foreach ($this->listOfFiles as $file) {
            $svg = new Svg();
            $fileData = file_get_contents($file['path'], "r");
            $fileData = preg_replace('/fill="[#0-9a-zA-z]+"/', '', $fileData);
            $fileName = $file['dir'] . '_' . $file['name'];
            $svg->setName($fileName);
            $svg->setSvg($fileData);
            $svg->setCategory($file['dir']);
            $manager->persist($svg);
        }
        $manager->flush();
    }

    public function loadSvgFiles($path)
    {
        foreach (array_diff(scandir($path), array('..', '.')) as $item) {

            if (is_dir($path . '/' . $item)) {
                $this->currentDirectory = $item;
                $this->loadSvgFiles($path . '/' . $item);
            } elseif (is_file($path . '/' . $item)) {
                $this->listOfFiles[] = [
                    'path' => $path,
                    'dir' => preg_split('/[a-z0-9A-Z\/]+\//', $path)[1],
                    'file' => $item,
                    'name' => preg_split('/[.][a-zA-Z0-9]+/', $item)[0],
                    'path' => $path . '/' . $item,
                    'type' => preg_split('/[a-zA-Z0-9\/]+[.]/', $item)[1],
                ];
            }
        }
    }

    public function getOrder()
    {
        return 1;
    }
}
