<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Interessentgrundmapping;
use AppBundle\Form\InteressentgrundmappingType;
use AppBundle\Controller\BaseController;

/**
 * Interessentgrundmapping controller.
 *
 * @Route("/interessentgrundmapping")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class InteressentgrundmappingController extends BaseController
{

  public function init(Request $request) {
    parent::init($request);
    $this->breadcrumbs->addItem('interessentgrundmapping.labels.singular', $this->generateUrl('interessentgrundmapping'));
}


    /**
     * Lists all Interessentgrundmapping entities.
     *
     * @Route("/", name="interessentgrundmapping")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Interessentgrundmapping')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Interessentgrundmapping entity.
     *
     * @Route("/", name="interessentgrundmapping_create")
     * @Method("POST")
     * @Template("AppBundle:Interessentgrundmapping:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Interessentgrundmapping();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('interessentgrundmapping'));

        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Interessentgrundmapping entity.
     *
     * @param Interessentgrundmapping $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Interessentgrundmapping $entity)
    {
        $form = $this->createForm('AppBundle\Form\InteressentgrundmappingType', $entity, array(
            'action' => $this->generateUrl('interessentgrundmapping_create'),
            'method' => 'POST',
        ));

        $this->addUpdate($form, $this->generateUrl('interessentgrundmapping'));

        return $form;
    }

    /**
     * Displays a form to create a new Interessentgrundmapping entity.
     *
     * @Route("/new", name="interessentgrundmapping_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $this->breadcrumbs->addItem('common.add', $this->generateUrl('interessentgrundmapping'));

        $entity = new Interessentgrundmapping();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Interessentgrundmapping entity.
     *
     * @Route("/{id}", name="interessentgrundmapping_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Interessentgrundmapping')->find($id);
        $this->breadcrumbs->addItem($entity, $this->generateUrl('interessentgrundmapping_show', array('id' => $entity->getId())));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Interessentgrundmapping entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Interessentgrundmapping entity.
     *
     * @Route("/{id}/edit", name="interessentgrundmapping_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction(Interessentgrundmapping $entity)
    {
        $this->breadcrumbs->addItem($entity, $this->generateUrl('interessentgrundmapping_show', array('id' => $entity->getId())));
        $this->breadcrumbs->addItem('common.edit', $this->generateUrl('interessentgrundmapping_show', array('id' => $entity->getId())));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Interessentgrundmapping entity.');
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
    * Creates a form to edit a Interessentgrundmapping entity.
    *
    * @param Interessentgrundmapping $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Interessentgrundmapping $entity)
    {
        $form = $this->createForm('AppBundle\Form\InteressentgrundmappingType', $entity, array(
            'action' => $this->generateUrl('interessentgrundmapping_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $this->addUpdate($form, $this->generateUrl('interessentgrundmapping_show', array('id' => $entity->getId())));

        return $form;
    }
    /**
     * Edits an existing Interessentgrundmapping entity.
     *
     * @Route("/{id}", name="interessentgrundmapping_update")
     * @Method("PUT")
     * @Template("AppBundle:Interessentgrundmapping:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Interessentgrundmapping')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Interessentgrundmapping entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('interessentgrundmapping'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Interessentgrundmapping entity.
     *
     * @Route("/{id}", name="interessentgrundmapping_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Interessentgrundmapping')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Interessentgrundmapping entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('interessentgrundmapping'));
    }

    /**
     * Creates a form to delete a Interessentgrundmapping entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('interessentgrundmapping_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
