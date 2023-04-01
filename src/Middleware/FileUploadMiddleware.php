<?php 

namespace App\Middleware;

use App\Entity\Image;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;

class FileUploadMiddleware {

    public function __construct( EntityManagerInterface $em)
    {
        $this->em = $em;
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
        $file->move($destination, $filename);

        $this->em->persist($image);
        $this->em->flush();

        return $image;
    }
}