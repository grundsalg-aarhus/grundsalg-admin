<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Keyword;
use AppBundle\Form\KeywordType;
use AppBundle\Controller\BaseController;

/**
 * Keyword controller.
 *
 * @Route("/keyword")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class KeywordController extends BaseController
{

  public function init(Request $request) {
    parent::init($request);
    $this->breadcrumbs->addItem('keyword.labels.singular', $this->generateUrl('keyword'));
}


    /**
     * Lists all Keyword entities.
     *
     * @Route("/", name="keyword")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Keyword')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Keyword entity.
     *
     * @Route("/", name="keyword_create")
     * @Method("POST")
     * @Template("AppBundle:Keyword:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Keyword();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('keyword'));

        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Keyword entity.
     *
     * @param Keyword $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Keyword $entity)
    {
        $form = $this->createForm('AppBundle\Form\KeywordType', $entity, array(
            'action' => $this->generateUrl('keyword_create'),
            'method' => 'POST',
        ));

        $this->addUpdate($form, $this->generateUrl('keyword'));

        return $form;
    }

    /**
     * Displays a form to create a new Keyword entity.
     *
     * @Route("/new", name="keyword_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $this->breadcrumbs->addItem('common.add', $this->generateUrl('keyword'));

        $entity = new Keyword();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Keyword entity.
     *
     * @Route("/{id}", name="keyword_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Keyword')->find($id);
        $this->breadcrumbs->addItem($entity, $this->generateUrl('keyword_show', array('id' => $entity->getId())));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Keyword entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Keyword entity.
     *
     * @Route("/{id}/edit", name="keyword_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction(Keyword $entity)
    {
        $this->breadcrumbs->addItem($entity, $this->generateUrl('keyword_show', array('id' => $entity->getId())));
        $this->breadcrumbs->addItem('common.edit', $this->generateUrl('keyword_show', array('id' => $entity->getId())));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Keyword entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($entity->getId());

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Keyword entity.
    *
    * @param Keyword $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Keyword $entity)
    {
        $form = $this->createForm('AppBundle\Form\KeywordType', $entity, array(
            'action' => $this->generateUrl('keyword_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $this->addUpdate($form, $this->generateUrl('keyword_show', array('id' => $entity->getId())));

        return $form;
    }
    /**
     * Edits an existing Keyword entity.
     *
     * @Route("/{id}", name="keyword_update")
     * @Method("PUT")
     * @Template("AppBundle:Keyword:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Keyword')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Keyword entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('keyword'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Keyword entity.
     *
     * @Route("/{id}", name="keyword_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Keyword')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Keyword entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('keyword'));
    }

    /**
     * Creates a form to delete a Keyword entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('keyword_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
