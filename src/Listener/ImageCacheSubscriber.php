<?php
/**
 * Created by PhpStorm.
 * User: matthieu
 * Date: 08/11/18
 * Time: 16:06
 */

namespace App\Listener;

use Doctrine\Common\EventSubscriber;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class ImageCacheSubscriber implements EventSubscriber
{
    public function __construct(CacheManager $cacheManager, UploaderHelper $uploaderHelper)
    {
    }

    public function getSubscribedEvents()
    {
        return [
            'preRemove',
            'preUpdate'
        ];
    }

}