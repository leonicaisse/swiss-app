<?php
/**
 * Created by PhpStorm.
 * User: leo
 * Date: 2020-01-09
 * Time: 09:51
 */

namespace App\Namer;

use Vich\UploaderBundle\Naming\DirectoryNamerInterface;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use App\Entity\Item;

class DirectoryNamer implements DirectoryNamerInterface
{
    /**
     * Returns the name of a directory where files will be uploaded
     *
     * @param Item $item
     * @param PropertyMapping $mapping
     *
     * @return string
     */
    function directoryName($item, PropertyMapping $mapping): string
    {
        $commandRef = $item->getCommand()->reference;
        $productRef = $item->getProduct()->reference;
        $path = $commandRef . '/' . $productRef . '/';
        return $path;
    }
}