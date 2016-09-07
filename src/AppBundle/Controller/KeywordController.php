<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Keyword;
use AppBundle\Form\KeywordType;

/**
 * Keyword controller.
 *
 * @Route("/keyword")
 */
class KeywordController extends Controller
{
    /**
     * Lists all Keyword entities.
     *
     * @Route("/", name="keyword_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $keywords = $em->getRepository('AppBundle:Keyword')->findAll();

        return $this->render('keyword/index.html.twig', array(
            'keywords' => $keywords,
        ));
    }

    /**
     * Creates a new Keyword entity.
     *
     * @Route("/new", name="keyword_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $keyword = new Keyword();
        $form = $this->createForm('AppBundle\Form\KeywordType', $keyword);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($keyword);
            $em->flush();

            return $this->redirectToRoute('keyword_show', array('id' => $keyword->getId()));
        }

        return $this->render('keyword/new.html.twig', array(
            'keyword' => $keyword,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Keyword entity.
     *
     * @Route("/{id}", name="keyword_show")
     * @Method("GET")
     */
    public function showAction(Keyword $keyword)
    {
        $deleteForm = $this->createDeleteForm($keyword);

        return $this->render('keyword/show.html.twig', array(
            'keyword' => $keyword,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Keyword entity.
     *
     * @Route("/{id}/edit", name="keyword_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Keyword $keyword)
    {
        $deleteForm = $this->createDeleteForm($keyword);
        $editForm = $this->createForm('AppBundle\Form\KeywordType', $keyword);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($keyword);
            $em->flush();

            return $this->redirectToRoute('keyword_edit', array('id' => $keyword->getId()));
        }

        return $this->render('keyword/edit.html.twig', array(
            'keyword' => $keyword,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Keyword entity.
     *
     * @Route("/{id}", name="keyword_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Keyword $keyword)
    {
        $form = $this->createDeleteForm($keyword);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($keyword);
            $em->flush();
        }

        return $this->redirectToRoute('keyword_index');
    }

    /**
     * Creates a form to delete a Keyword entity.
     *
     * @param Keyword $keyword The Keyword entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Keyword $keyword)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('keyword_delete', array('id' => $keyword->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
