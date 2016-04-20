<?php

namespace PessoaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/pessoa", name="home")
     */
    public function indexAction()
    {
        return $this->render('PessoaBundle:Default:index.html.twig');
    }
}
