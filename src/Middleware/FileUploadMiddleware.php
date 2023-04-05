<?php 

namespace App\Middleware;

use DateTime;
use App\Entity\Image;
use App\Repository\NovelImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;

class FileUploadMiddleware {

    public function __construct(EntityManagerInterface $em, private NovelImageRepository $novelImageRepository, private string $kernelProjectDir )
    {
        $this->em = $em;
        $this->kernelProjectDir = $kernelProjectDir;
    }
    
    public function imageUpload($file = null, $destination = null) : Image | bool
    {
        if ($file === null) {
            return false;
        }
        
        $slugger = new AsciiSlugger();
        $imageExtension = $file->guessExtension();
        $filename = (new DateTime())->format('YmdHis') . '-' . $slugger->slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)).'.'.$imageExtension;

        $allowedImages = array('jpg', 'jpeg', 'png');

        if (!in_array($imageExtension, $allowedImages)) {
            return false;
        }

        $image = new Image;
        $image->setFilename($filename);
        $image->setFilepath($destination . '/' . $filename);

        $projectDir = $this->kernelProjectDir;
        $file->move($projectDir . '/public' . $destination, $filename);

        $this->em->persist($image);
        $this->em->flush();

        return $image;
    }

    public function imageDelete(Image $image = null, $destination = null) : bool
    {
        if ($image === null) {
            return false;
        }

        $filename = $image->getFilename();
        
        //remove from novelImages
        $novelImages = $this->novelImageRepository->findBy(['image' => $image->getId()]);
        foreach ($novelImages as $novelImage) {
            $this->em->remove($novelImage);
        }
        $this->em->remove($image);
        $this->em->flush();

        unlink($destination.'/'.$filename);

        return true;
    }
}