<?php
namespace Entity;

use Doctrine\ORM\Mapping as ORM;
/**
* @ORM\Entity
* @ORM\Table(name="smf_mailchimp_report")
*/
class MailchimpReport
{
    /**
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    * @ORM\Column(type="integer")
    */
    protected $id;

    /** @ORM\Column(type="string") */
    protected $title;

    /** @ORM\Column(type="string") */
    protected $campaign_id;

    /** @ORM\Column(type="integer") */
    protected $send_timestamp;

    public function getId()
    {
        return $this->id;
    }
}