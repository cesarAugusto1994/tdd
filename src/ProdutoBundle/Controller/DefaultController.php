<?php

namespace ProdutoBundle\Controller;

use ProdutoBundle\Entity\Produto;
use Proxies\__CG__\ProdutoBundle\Entity\Categoria;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use ProdutoBundle\Form\CategoriaType;


class DefaultController extends Controller
{
    /**
     * @Route("/", name="produtos")
     */
    public function indexAction()
    {
        $produtos = $this->getDoctrine()->getRepository('ProdutoBundle:Produto');
        return $this->render('@Produto/Default/index.html.twig', array('produtos' => $produtos->findAll()));
    }

    /**
     * @Route("/cadastrar", name="cadastrar")
     */
    public function cadastrarAction(Request $request)
    {
        if (empty($request->get('nome'))) {
            return $this->redirectToRoute('produtos');
        }

        $produto = new Produto();

        $produto->setNome($request->get('nome'));
        $produto->setDescricao($request->get('desc'));
        $produto->setValor($request->get('valor'));

        $this->getDoctrine()->getRepository('ProdutoBundle:Produto')->save($produto);

        return $this->redirectToRoute('produtos');
    }

    /**
     * @Route("/novo", name="novo")
     */
    public function newAction(Request $request)
    {

        $categorias = $this->getDoctrine()->getRepository('ProdutoBundle:Categoria')->findAll();

        $array = function ($categorias) {
            foreach ($categorias as $categoria) {
                yield [$categoria->getNome() => $categoria->getId()];
            }
        };

        $produto = new Produto();

        $form = $this->createFormBuilder($produto)
            ->add('nome', TextType::class)
            ->add('descricao', TextType::class)
            ->add('valor')
            ->add(
                'categoria',
                ChoiceType::class,
                array(
                    'choices' => $array($categorias)
                )
            )
            ->add('Gravar', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $dadosRequest = $request->request->get('form');

            $categoria = $this->getDoctrine()->getRepository('ProdutoBundle:Categoria')->find(
                (int)$dadosRequest['categoria']
            );

            $produto->setNome($dadosRequest['nome']);
            $produto->setDescricao($dadosRequest['descricao']);
            $produto->setValor($dadosRequest['valor']);
            $produto->setCategoria($categoria);
            $produto->setCadastro(new \DateTime());
            $produto->setAtivo(true);

            $this->getDoctrine()->getRepository('ProdutoBundle:Produto')->save($produto);

            return $this->redirectToRoute('produtos', ['sucesso' => true]);
        }

        return $this->render(
            'ProdutoBundle:Default:cadastro.html.twig',
            array('form' => $form->createView(), 'sucesso' => true)
        );
    }

    /**
     * @Route("/ativo/{ativo}", name="ativo")
     */
    public function find($ativo)
    {
        $repository = $this->getDoctrine()->getRepository('ProdutoBundle:Produto');

        return $repository->findBy(['ativo' => $ativo]);
    }

    /**
     * @Route("/{produto}/{status}", name="setStatus")
     */
    public function setStatus(Produto $produto, $status)
    {
        $produto->setAtivo($status);

        $this->getDoctrine()->getRepository('ProdutoBundle:Produto')->save($produto);
    }
}
