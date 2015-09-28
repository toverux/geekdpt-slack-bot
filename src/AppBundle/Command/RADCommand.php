<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

abstract class RADCommand extends ContainerAwareCommand
{
    /**
     * Gets a container service by its id.
     *
     * @param string $id The service id
     *
     * @return object The service
     */
    public function get($id)
    {
        return $this->getContainer()->get($id);
    }

    protected function getDEM() # DEM stands for Doctrine Entity Manager
    {
        return $this->get('doctrine.orm.entity_manager');
    }

    protected function getDEMRepository($entity)
    {
        return $this->getDEM()->getRepository($entity);
    }
}
