<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactoController extends AbstractController
{
    
      #[Route('/contacto/{codigo}', name: 'ficha_contacto')]
   
    public function ficha($codigo): Response{
    //Si no existe el elemento con dicha clave devolvemos null
    $resultado = ($this->contactos[$codigo] ?? null);

    return $this->render('ficha_contacto.html.twig', [
    'contacto' => $resultado
    ]);
}
}