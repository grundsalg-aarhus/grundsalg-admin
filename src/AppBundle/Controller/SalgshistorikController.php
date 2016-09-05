<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Salgshistorik;
use AppBundle\Form\SalgshistorikType;
use AppBundle\Controller\BaseController;

/**
 * Salgshistorik controller.
 *
 * @Route("/salgshistorik")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class SalgshistorikController extends BaseController
{

  public function init(Request $request) {
    parent::init($request);
    $this->breadcrumbs->addItem('salgshistorik.labels.singular', $this->generateUrl('salgshistorik'));
}


    /**
     * Lists all Salgshistorik entities.
     *
     * @Route("/", name="salgshistorik")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Salgshistorik')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Salgshistorik entity.
     *
     * @Route("/", name="salgshistorik_create")
     * @Method("POST")
     * @Template("AppBundle:Salgshistorik:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Salgshistorik();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('salgshistorik'));

        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Salgshistorik entity.
     *
     * @param Salgshistorik $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Salgshistorik $entity)
    {
        $form = $this->createForm('AppBundle\Form\SalgshistorikType', $entity, array(
            'action' => $this->generateUrl('salgshistorik_create'),
            'method' => 'POST',
        ));

        $this->addUpdate($form, $this->generateUrl('salgshistorik'));

        return $form;
    }

    /**
     * Displays a form to create a new Salgshistorik entity.
     *
     * @Route("/new", name="salgshistorik_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $this->breadcrumbs->addItem('common.add', $this->generateUrl('salgshistorik'));

        $entity = new Salgshistorik();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Salgshistorik entity.
     *
     * @Route("/{id}", name="salgshistorik_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Salgshistorik')->find($id);
        $this->breadcrumbs->addItem($entity, $this->generateUrl('salgshistorik_show', array('id' => $entity->getId())));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Salgshistorik entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Salgshistorik entity.
     *
     * @Route("/{id}/edit", name="salgshistorik_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction(Salgshistorik $entity)
    {
        $this->breadcrumbs->addItem($entity, $this->generateUrl('salgshistorik_show', array('id' => $entity->getId())));
        $this->breadcrumbs->addItem('common.edit', $this->generateUrl('salgshistorik_show', array('id' => $entity->getId())));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Salgshistorik entity.');
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
    * Creates a form to edit a Salgshistorik entity.
    *
    * @param Salgshistorik $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Salgshistorik $entity)
    {
        $form = $this->createForm('AppBundle\Form\SalgshistorikType', $entity, array(
            'action' => $this->generateUrl('salgshistorik_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $this->addUpdate($form, $this->generateUrl('salgshistorik_show', array('id' => $entity->getId())));

        return $form;
    }
    /**
     * Edits an existing Salgshistorik entity.
     *
     * @Route("/{id}", name="salgshistorik_update")
     * @Method("PUT")
     * @Template("AppBundle:Salgshistorik:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Salgshistorik')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Salgshistorik entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('salgshistorik'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Salgshistorik entity.
     *
     * @Route("/{id}", name="salgshistorik_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Salgshistorik')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Salgshistorik entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('salgshistorik'));
    }

    /**
     * Creates a form to delete a Salgshistorik entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('salgshistorik_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
