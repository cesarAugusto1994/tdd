<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DefaultController
 * @package AppBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $produtos = $this->getDoctrine()->getRepository('ProdutoBundle:Produto');

        return $this->render('@App/index.html.twig', array('produtos' => $produtos->findAll()));
    }

    /**
     * @Route("/search", name="search")
     */
    public function searchAction(Request $request)
    {
        $search = $request->get('search');

        $produtos = $this->getDoctrine()->getRepository('ProdutoBundle:Produto')->search($search);

        return $this->render('@App/index.html.twig', array('produtos' => $produtos, 'param' => $search));
    }

    /**
     * @Route("/categorias/{categoria}", name="buscarPorCategoria")
     */
    public function searchByCategoria($categoria)
    {
        $produtos = $this->getDoctrine()->getRepository('ProdutoBundle:Produto')->search($categoria);

        return $this->render('@App/index.html.twig', array('produtos' => $produtos, 'param' => $categoria));
    }
}
