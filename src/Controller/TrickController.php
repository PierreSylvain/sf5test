<?php


namespace App\Controller;

use App\Entity\Trick;
use App\Entity\Media;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\TrickType;
/*
use App\Repository\TrickRepository;
;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Service\UploaderHelper;
use App\Service\VideoUploader;
use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\Common\Persistence\ObjectManager;
*/
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
class TrickController extends AbstractController
{
    /**
     * Ajouter une figure
     * @Route("trick/add")
     * @param Request $request
     * @return Response
     */
    public function add(Request $request) : Response
    {
        // Create trick entity
        $trick = new Trick();

        // Create First media and add to trick
        $media = new Media();
        $trick->getMedias()->add($media);

        // Create Second media and add to trick
        //$media2 = new Media();       
        //$trick->getMedias()->add($media2);
       
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            // à faire plutôt dans un service
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($trick);
            $entityManager->flush();
            
            $files = $trick->getMedias(); 
            foreach($files as $file){
                $media = new Media();
                $media->setName($file->getName());
                $media->setCaption($file->getCaption());
                $media->setUrl('xxxx');
                $now = new \DateTime();
                $media->setDateAdd($now);
                $media->setTrick($trick);
                $entityManager->persist($media);
                $entityManager->flush(); 
            }                            
        }
        return $this->render('add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
