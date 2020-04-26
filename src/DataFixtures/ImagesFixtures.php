<?php

namespace App\DataFixtures;

use App\Entity\Images;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ImagesFixtures extends Fixture implements OrderedFixtureInterface
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
        // $product = new Product();
        // $manager->persist($product);
        $dirs = $this->container->getParameter('images_directory').'/svgs';
        $this->currentDirectory = "images";

        $this->loadImageFiles($dirs);
        foreach ($this->listOfFiles as $file) {
            $image = new Images();
            $fileData = file_get_contents($file['path'], "r");
            $fileData = preg_replace('/fill="[#0-9a-zA-z]+"/', '', $fileData);
            $fileName = $file['dir'] . '_' . $file['name'];
            $image->setName($fileName);
            $image->setSvg($fileData);
            $image->setCategory($file['dir']);
            $manager->persist($image);
        }
        $manager->flush();
    }

    public function loadImageFiles($path)
    {
        foreach (array_diff(scandir($path), array('..', '.')) as $item) {

            if (is_dir($path . '/' . $item)) {
                $this->currentDirectory = $item;
                $this->loadImageFiles($path . '/' . $item);
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
