<?php
/**
 * Created by IntelliJ IDEA.
 * User: eletue
 * Date: 22/09/2017
 * Time: 11:19
 */

namespace App\Storage;

use App\Entity\News;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class LastUpdatedNewsStorage
{
    const STORAGE_KEY_NAME = "news.last_updated";

    /**
     * @var SessionInterface
     */
    private $storage;

    /**
     * LastUpdatedNewsStorage constructor.
     * @param SessionInterface $storage
     */
    public function __construct(SessionInterface $storage)
    {
        $this->storage = $storage;
    }

    public function set(News $news): void
    {
        $this->storage->set(static::STORAGE_KEY_NAME, $news);
    }

    public function get(): ?News
    {
        return $this->storage->get(static::STORAGE_KEY_NAME);
    }

    public function remove(News $news): void
    {
        if ($news->getId() === $this->get()->getId()) $this->storage->remove(static::STORAGE_KEY_NAME);
    }
}