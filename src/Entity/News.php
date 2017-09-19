<?php
/**
 * @author Erwann LETUE <erwann.letue@gmail.com>
 * Date: 19/09/2017 at 16:32
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class News
 * @package App\Entity
 * @ORM\Entity()
 */
class News
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    protected $title;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    protected $content;
}