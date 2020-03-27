<?php


namespace App\Controller;

use App\Entity\Trick;
use App\Entity\Media;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\TrickType;

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

            // Manage uploded files
            $uploads = $trick->getMedias(); 
            
            // Show data
            //dump($uploads);die;

            foreach($uploads as $upload){
                /** @var UploadedFile $file */
                $file = $upload->getFile(); 
                /*
                     Do what you have to do with UploadedFile class
                     ----------------------------------------------

                Symfony\Component\HttpFoundation\File\UploadedFile Object
                (
                    [test:Symfony\Component\HttpFoundation\File\UploadedFile:private] => 
                    [originalName:Symfony\Component\HttpFoundation\File\UploadedFile:private] => filename.pdf
                    [mimeType:Symfony\Component\HttpFoundation\File\UploadedFile:private] => application/pdf
                    [error:Symfony\Component\HttpFoundation\File\UploadedFile:private] => 0
                    [pathName:SplFileInfo:private] => /tmp/phpYwBKvJ
                    [fileName:SplFileInfo:private] => phpYwBKvJ
                )
                */
                
                $media = new Media();
                $media->setName('name-of-the-file.png');
                $media->setCaption($upload->getCaption());
                $media->setUrl('xxxx');
                $now = new \DateTime();
                $media->setDateAdd($now);
                $media->setTrick($trick);
                $entityManager->persist($media);
                $entityManager->flush(); 
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($trick);
            $entityManager->flush();            
        }
        return $this->render('add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
