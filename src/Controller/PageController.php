<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Contacto;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ContactoRepository;


final class PageController extends AbstractController

{

    private $contactos = [

        1 => ["nombre" => "Juan Pérez", "telefono" => "524142432", "email" => "juanp@ieselcaminas.org"],

        2 => ["nombre" => "Ana López", "telefono" => "58958448", "email" => "anita@ieselcaminas.org"],

        5 => ["nombre" => "Mario Montero", "telefono" => "5326824", "email" => "mario.mont@ieselcaminas.org"],

        7 => ["nombre" => "Laura Martínez", "telefono" => "42898966", "email" => "lm2000@ieselcaminas.org"],

        9 => ["nombre" => "Nora Jover", "telefono" => "54565859", "email" => "norajover@ieselcaminas.org"]

    ];



    #[Route('contacto/insertar', name: 'insertar_contacto')]
    public function insertar(ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();
        foreach($this->contactos as $c){
            $contacto = new Contacto();
            $contacto->setNombre($c['nombre']);
            $contacto->setTelefono($c['telefono']);
            $contacto->setEmail($c['email']);
            $entityManager->persist($contacto);
        }
        try {
            $entityManager->flush();
            return new Response('Contactos insertados correctamente');
        } catch (\Exception $e) {
            return new Response('Error al insertar los contactos: ' . $e->getMessage());
        }
    }
    #[Route('/page', name: 'app_page')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/PageController.php',
        ]);
    }
    #[Route('/', name: 'inicio')]
    public function inicio(): Response
    {
        return $this->render('inicio.html.twig');
            
    }

    

    #[Route('/contacto/{nombre?Juan Pérez}', name: 'ficha_contacto')]

    public function ficha(ManagerRegistry $doctrine, $nombre): Response{
        $repositorio = $doctrine->getRepository(Contacto::class);

        //Si no existe el elemento con dicha clave devolvemos null
        $contacto = $repositorio->find($nombre);

        return $this->render('ficha_contacto.html.twig', [
        'contacto' => $contacto
        ]);
    }

    #[Route('/contactos/buscar/{texto}', name: 'buscar_contacto')]
    public function buscar(ContactoRepository $repositorio, $texto): Response{

        //Si no existe el elemento con dicha clave devolvemos null
        $contacto = $repositorio->findByName($texto);

        return $this->render('lista_contacto.html.twig', [
        'contactos' => $contacto
        ]);
    }

    #[Route('/contacto/update/{telefono}/{nombre}', name: 'modificar_contacto')]
    public function update(ManagerRegistry $doctrine,$telefono, $nombre): Response{
        $entityManager = $doctrine->getManager();
        $repositorio = $doctrine->getRepository(Contacto::class);
        $contacto = $repositorio->find($nombre);
        if ($contacto){
            $contacto->setTelefono($telefono);
            try {
                $entityManager->flush();
                return $this->render('ficha_contacto.html.twig', [
                    'contacto' => $contacto
                ]);
            } catch (\Exception $e) {
                return new Response('Error al modificar el contacto: ' . $e->getMessage());
            }   
        } else 
            return $this->render('ficha_contacto.html.twig', [
                'contacto' => null
            ]);
    }

    #[Route('/contacto/delete/{nombre}', name: 'modificar_contacto')]
    public function delete(ManagerRegistry $doctrine,$nombre): Response{
        $entityManager = $doctrine->getManager();
        $repositorio = $doctrine->getRepository(Contacto::class);
        $contacto = $repositorio->find($nombre);
        if ($contacto){
            try {
                $entityManager->remove($contacto);
                $entityManager->flush();
                return new Response("Contacto eliminado correctamente");
            } catch (\Exception $e) {
                return new Response("Error al eliminar.");
            }   
        } else 
            return $this->render('ficha_contacto.html.twig', [
                'contacto' => null
            ]);
    }
}
     
