<?php
/**
 * User: Erwann
 * Date: 19/09/2017
 * Time: 15:14
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;


/**
 * Class User
 * @package App\Entity
 * @ORM\Entity()
 * @ApiResource()
 */
class User extends BaseUser
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var News
     * @ORM\OneToMany(targetEntity="App\Entity\News", mappedBy="author")
     */
    protected $newsList;

    /**
     * @var Album
     * @ORM\OneToMany(targetEntity="App\Entity\Album", mappedBy="author")
     */
    protected $albumList;
}