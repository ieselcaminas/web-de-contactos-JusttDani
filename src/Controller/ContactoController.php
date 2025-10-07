<?php

namespace App\Controller;

use App\Entity\Contacto; 
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactoController
{
     protected $contactos = [

        1 => ["nombre" => "Juan Pérez", "telefono" => "524142432", "email" => "juanp@ieselcaminas.org"],

        2 => ["nombre" => "Ana López", "telefono" => "58958448", "email" => "anita@ieselcaminas.org"],

        5 => ["nombre" => "Mario Montero", "telefono" => "5326824", "email" => "mario.mont@ieselcaminas.org"],

        7 => ["nombre" => "Laura Martínez", "telefono" => "42898966", "email" => "lm2000@ieselcaminas.org"],

        9 => ["nombre" => "Nora Jover", "telefono" => "54565859", "email" => "norajover@ieselcaminas.org"]

    ];

    #[Route('/contacto/insertar', name: 'insertar_contacto')]
    public function insertar(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        foreach ($this->contactos as $c) {
            $contacto = new Contacto();
            $contacto->setNombre($c["nombre"]); 
            $contacto->setTelefono($c["telefono"]); 
            $contacto->setEmail($c["email"]); 
            $entityManager->persist($contacto);
        }

        try {
            $entityManager->flush(); 
            return new Response("Contactos insertados correctamente."); 
        } catch (\Exception $e) {
            return new Response("Error insertando objetos: " . $e->getMessage());
        }
    }
}
