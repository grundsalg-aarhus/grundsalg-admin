<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Delomraade;
use AppBundle\Form\DelomraadeType;
use AppBundle\Controller\BaseController;

/**
 * Delomraade controller.
 *
 * @Route("/delomraade")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class DelomraadeController extends BaseController
{

  public function init(Request $request) {
    parent::init($request);
    $this->breadcrumbs->addItem('delomraade.labels.singular', $this->generateUrl('delomraade'));
}


    /**
     * Lists all Delomraade entities.
     *
     * @Route("/", name="delomraade")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Delomraade')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Delomraade entity.
     *
     * @Route("/", name="delomraade_create")
     * @Method("POST")
     * @Template("AppBundle:Delomraade:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Delomraade();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('delomraade'));

        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Delomraade entity.
     *
     * @param Delomraade $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Delomraade $entity)
    {
        $form = $this->createForm('AppBundle\Form\DelomraadeType', $entity, array(
            'action' => $this->generateUrl('delomraade_create'),
            'method' => 'POST',
        ));

        $this->addUpdate($form, $this->generateUrl('delomraade'));

        return $form;
    }

    /**
     * Displays a form to create a new Delomraade entity.
     *
     * @Route("/new", name="delomraade_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $this->breadcrumbs->addItem('common.add', $this->generateUrl('delomraade'));

        $entity = new Delomraade();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Delomraade entity.
     *
     * @Route("/{id}", name="delomraade_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Delomraade')->find($id);
        $this->breadcrumbs->addItem($entity, $this->generateUrl('delomraade_show', array('id' => $entity->getId())));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Delomraade entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Delomraade entity.
     *
     * @Route("/{id}/edit", name="delomraade_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction(Delomraade $entity)
    {
        $this->breadcrumbs->addItem($entity, $this->generateUrl('delomraade_show', array('id' => $entity->getId())));
        $this->breadcrumbs->addItem('common.edit', $this->generateUrl('delomraade_show', array('id' => $entity->getId())));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Delomraade entity.');
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
    * Creates a form to edit a Delomraade entity.
    *
    * @param Delomraade $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Delomraade $entity)
    {
        $form = $this->createForm('AppBundle\Form\DelomraadeType', $entity, array(
            'action' => $this->generateUrl('delomraade_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $this->addUpdate($form, $this->generateUrl('delomraade_show', array('id' => $entity->getId())));

        return $form;
    }
    /**
     * Edits an existing Delomraade entity.
     *
     * @Route("/{id}", name="delomraade_update")
     * @Method("PUT")
     * @Template("AppBundle:Delomraade:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Delomraade')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Delomraade entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('delomraade'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Delomraade entity.
     *
     * @Route("/{id}", name="delomraade_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Delomraade')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Delomraade entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('delomraade'));
    }

    /**
     * Creates a form to delete a Delomraade entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('delomraade_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
