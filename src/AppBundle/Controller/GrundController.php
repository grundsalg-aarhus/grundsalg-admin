<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Grund;
use AppBundle\Form\GrundType;
use AppBundle\Controller\BaseController;

/**
 * Grund controller.
 *
 * @Route("/grund")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class GrundController extends BaseController
{

  public function init(Request $request) {
    parent::init($request);
    $this->breadcrumbs->addItem('grund.labels.singular', $this->generateUrl('grund'));
}


    /**
     * Lists all Grund entities.
     *
     * @Route("/", name="grund")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Grund')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Grund entity.
     *
     * @Route("/", name="grund_create")
     * @Method("POST")
     * @Template("AppBundle:Grund:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Grund();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('grund'));

        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Grund entity.
     *
     * @param Grund $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Grund $entity)
    {
        $form = $this->createForm('AppBundle\Form\GrundType', $entity, array(
            'action' => $this->generateUrl('grund_create'),
            'method' => 'POST',
        ));

        $this->addUpdate($form, $this->generateUrl('grund'));

        return $form;
    }

    /**
     * Displays a form to create a new Grund entity.
     *
     * @Route("/new", name="grund_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $this->breadcrumbs->addItem('common.add', $this->generateUrl('grund'));

        $entity = new Grund();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Grund entity.
     *
     * @Route("/{id}", name="grund_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Grund')->find($id);
        $this->breadcrumbs->addItem($entity, $this->generateUrl('grund_show', array('id' => $entity->getId())));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Grund entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Grund entity.
     *
     * @Route("/{id}/edit", name="grund_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction(Grund $entity)
    {
        $this->breadcrumbs->addItem($entity, $this->generateUrl('grund_show', array('id' => $entity->getId())));
        $this->breadcrumbs->addItem('common.edit', $this->generateUrl('grund_show', array('id' => $entity->getId())));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Grund entity.');
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
    * Creates a form to edit a Grund entity.
    *
    * @param Grund $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Grund $entity)
    {
        $form = $this->createForm('AppBundle\Form\GrundType', $entity, array(
            'action' => $this->generateUrl('grund_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $this->addUpdate($form, $this->generateUrl('grund_show', array('id' => $entity->getId())));

        return $form;
    }
    /**
     * Edits an existing Grund entity.
     *
     * @Route("/{id}", name="grund_update")
     * @Method("PUT")
     * @Template("AppBundle:Grund:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Grund')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Grund entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('grund'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Grund entity.
     *
     * @Route("/{id}", name="grund_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Grund')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Grund entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('grund'));
    }

    /**
     * Creates a form to delete a Grund entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('grund_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
