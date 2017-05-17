<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Grund;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;

/**
 * @Route("/bulk")
 */
class BulkController extends Controller {
  /**
   *
   * @Route("/update", name="bulk_update")
   * @Method("POST")
   */
  public function bulkUpdateAction(Request $request) {
    $bulk = $request->get('bulk');
    $data = $request->get('data');
    unset($data['_token']);
    // Remove empty values.
    $data = array_filter($data);
    $ids = $bulk['ids'];

    $translator = $this->get('translator');
    $succes_ids = array();

    if (empty($data) || empty($ids)) {
      $this->addFlash('warning', $translator->trans('Nothing to update'));
    } else {
      $em = $this->getDoctrine()->getManager();
      $repository = $em->getRepository(Grund::class);
      $accessor = $this->get('property_accessor');
      foreach ($ids as $id) {
        $entity = $repository->find($id);
        if (!$entity) {
          $this->addFlash('warning', $translator->trans('Cannot find entity with @id', ['@id' => $id]));
        } else {
          $errors = [];
          foreach ($data as $name => $value) {
            if ($accessor->isWritable($entity, $name)) {
              try {
                $accessor->setValue($entity, $name, $value);
              } catch (\Exception $e) {
                $errors[] = $e;
              }
            }
          }
          if ($errors) {
            $this->addFlash('danger', $translator->trans('Error updating @entity', ['@entity' => $entity->_toString()]));
          }
          else {
            $em->persist($entity);
            $em->flush();

            $succes_ids[] = $entity->getId();
          }
        }
      }
    }

    if(!empty($succes_ids)) {
      $this->addFlash('success', $translator->trans('@count entities updated', ['@count' => count($succes_ids)]));
    }

    return $this->redirectToRoute('easyadmin', $request->query->all());
  }

  /**
   *
   * @Route("/bulk/delete", name="grund_bulk_delete")
   * @Method("POST")
   */
  public function bulkDeleteAction(Request $request) {
    $bulk = $request->get('bulk');
    $ids = $bulk['ids'];
    $translator = $this->get('translator');

    header('Content-type: text/plain'); echo var_export(['ids' => $ids], true); die(__FILE__.':'.__LINE__.':'.__METHOD__);
  }
}
