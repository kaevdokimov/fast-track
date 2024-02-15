<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ConferenceController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(Request $request): Response
    {
        return new Response(<<<EOF
            <html>
                <body>
                    <img style="margin: 0 auto; display: flex;" src="/images/under-construction.gif" />
                </body>
            </html>
            EOF
        );
    }
}
