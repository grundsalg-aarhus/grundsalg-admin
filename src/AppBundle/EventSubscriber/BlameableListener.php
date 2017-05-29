<?php

namespace AppBundle\EventSubscriber;


/**
 * Extend the default listener to use 'name' instead of 'username'
 */
class BlameableListener extends \Gedmo\Blameable\BlameableListener
{

    /**
     * Get the user value to set on a blameable field
     *
     * @param object $meta
     * @param string $field
     *
     * @return mixed
     */
    public function getFieldValue($meta, $field, $eventAdapter)
    {
        if ($meta->hasAssociation($field)) {
            if (null !== $this->user && ! is_object($this->user)) {
                throw new InvalidArgumentException("Blame is reference, user must be an object");
            }

            return $this->user;
        }

        // ok so its not an association, then it is a string
        if (is_object($this->user)) {
            if (method_exists($this->user, 'getName')) {
                return (string) $this->user->getName();
            }
            if (method_exists($this->user, '__toString')) {
                return $this->user->__toString();
            }
            throw new InvalidArgumentException("Field expects string, user must be a string, or object should have method getUsername or __toString");
        }

        return $this->user;
    }

}
