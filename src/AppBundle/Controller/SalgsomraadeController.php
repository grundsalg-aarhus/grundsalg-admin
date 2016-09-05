<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Salgsomraade;
use AppBundle\Form\SalgsomraadeType;
use AppBundle\Controller\BaseController;

/**
 * Salgsomraade controller.
 *
 * @Route("/salgsomraade")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class SalgsomraadeController extends BaseController
{

  public function init(Request $request) {
    parent::init($request);
    $this->breadcrumbs->addItem('salgsomraade.labels.singular', $this->generateUrl('salgsomraade'));
}


    /**
     * Lists all Salgsomraade entities.
     *
     * @Route("/", name="salgsomraade")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Salgsomraade')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Salgsomraade entity.
     *
     * @Route("/", name="salgsomraade_create")
     * @Method("POST")
     * @Template("AppBundle:Salgsomraade:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Salgsomraade();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('salgsomraade'));

        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Salgsomraade entity.
     *
     * @param Salgsomraade $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Salgsomraade $entity)
    {
        $form = $this->createForm('AppBundle\Form\SalgsomraadeType', $entity, array(
            'action' => $this->generateUrl('salgsomraade_create'),
            'method' => 'POST',
        ));

        $this->addUpdate($form, $this->generateUrl('salgsomraade'));

        return $form;
    }

    /**
     * Displays a form to create a new Salgsomraade entity.
     *
     * @Route("/new", name="salgsomraade_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $this->breadcrumbs->addItem('common.add', $this->generateUrl('salgsomraade'));

        $entity = new Salgsomraade();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Salgsomraade entity.
     *
     * @Route("/{id}", name="salgsomraade_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Salgsomraade')->find($id);
        $this->breadcrumbs->addItem($entity, $this->generateUrl('salgsomraade_show', array('id' => $entity->getId())));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Salgsomraade entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Salgsomraade entity.
     *
     * @Route("/{id}/edit", name="salgsomraade_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction(Salgsomraade $entity)
    {
        $this->breadcrumbs->addItem($entity, $this->generateUrl('salgsomraade_show', array('id' => $entity->getId())));
        $this->breadcrumbs->addItem('common.edit', $this->generateUrl('salgsomraade_show', array('id' => $entity->getId())));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Salgsomraade entity.');
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
    * Creates a form to edit a Salgsomraade entity.
    *
    * @param Salgsomraade $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Salgsomraade $entity)
    {
        $form = $this->createForm('AppBundle\Form\SalgsomraadeType', $entity, array(
            'action' => $this->generateUrl('salgsomraade_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $this->addUpdate($form, $this->generateUrl('salgsomraade_show', array('id' => $entity->getId())));

        return $form;
    }
    /**
     * Edits an existing Salgsomraade entity.
     *
     * @Route("/{id}", name="salgsomraade_update")
     * @Method("PUT")
     * @Template("AppBundle:Salgsomraade:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Salgsomraade')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Salgsomraade entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('salgsomraade'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Salgsomraade entity.
     *
     * @Route("/{id}", name="salgsomraade_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Salgsomraade')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Salgsomraade entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('salgsomraade'));
    }

    /**
     * Creates a form to delete a Salgsomraade entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('salgsomraade_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
