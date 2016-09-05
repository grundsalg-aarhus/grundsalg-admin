<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Lokalplan;
use AppBundle\Form\LokalplanType;
use AppBundle\Controller\BaseController;

/**
 * Lokalplan controller.
 *
 * @Route("/lokalplan")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class LokalplanController extends BaseController
{

  public function init(Request $request) {
    parent::init($request);
    $this->breadcrumbs->addItem('lokalplan.labels.singular', $this->generateUrl('lokalplan'));
}


    /**
     * Lists all Lokalplan entities.
     *
     * @Route("/", name="lokalplan")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Lokalplan')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Lokalplan entity.
     *
     * @Route("/", name="lokalplan_create")
     * @Method("POST")
     * @Template("AppBundle:Lokalplan:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Lokalplan();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('lokalplan'));

        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Lokalplan entity.
     *
     * @param Lokalplan $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Lokalplan $entity)
    {
        $form = $this->createForm('AppBundle\Form\LokalplanType', $entity, array(
            'action' => $this->generateUrl('lokalplan_create'),
            'method' => 'POST',
        ));

        $this->addUpdate($form, $this->generateUrl('lokalplan'));

        return $form;
    }

    /**
     * Displays a form to create a new Lokalplan entity.
     *
     * @Route("/new", name="lokalplan_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $this->breadcrumbs->addItem('common.add', $this->generateUrl('lokalplan'));

        $entity = new Lokalplan();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Lokalplan entity.
     *
     * @Route("/{id}", name="lokalplan_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Lokalplan')->find($id);
        $this->breadcrumbs->addItem($entity, $this->generateUrl('lokalplan_show', array('id' => $entity->getId())));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Lokalplan entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Lokalplan entity.
     *
     * @Route("/{id}/edit", name="lokalplan_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction(Lokalplan $entity)
    {
        $this->breadcrumbs->addItem($entity, $this->generateUrl('lokalplan_show', array('id' => $entity->getId())));
        $this->breadcrumbs->addItem('common.edit', $this->generateUrl('lokalplan_show', array('id' => $entity->getId())));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Lokalplan entity.');
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
    * Creates a form to edit a Lokalplan entity.
    *
    * @param Lokalplan $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Lokalplan $entity)
    {
        $form = $this->createForm('AppBundle\Form\LokalplanType', $entity, array(
            'action' => $this->generateUrl('lokalplan_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $this->addUpdate($form, $this->generateUrl('lokalplan_show', array('id' => $entity->getId())));

        return $form;
    }
    /**
     * Edits an existing Lokalplan entity.
     *
     * @Route("/{id}", name="lokalplan_update")
     * @Method("PUT")
     * @Template("AppBundle:Lokalplan:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Lokalplan')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Lokalplan entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('lokalplan'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Lokalplan entity.
     *
     * @Route("/{id}", name="lokalplan_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Lokalplan')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Lokalplan entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('lokalplan'));
    }

    /**
     * Creates a form to delete a Lokalplan entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('lokalplan_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
