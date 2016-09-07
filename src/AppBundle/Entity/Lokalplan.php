<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Lokalplan
 *
 * @ORM\Table(name="Lokalplan")
 * @ORM\Entity
 */
class Lokalplan
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nr", type="text", nullable=false)
     */
    private $nr;

    /**
     * @var string
     *
     * @ORM\Column(name="lsnr", type="text", nullable=false)
     */
    private $lsnr;

    /**
     * @var string
     *
     * @ORM\Column(name="titel", type="text", nullable=false)
     */
    private $titel;

    /**
     * @var string
     *
     * @ORM\Column(name="projektLeder", type="text", nullable=false)
     */
    private $projektleder;

    /**
     * @var string
     *
     * @ORM\Column(name="telefon", type="text", nullable=false)
     */
    private $telefon;

    /**
     * @var string
     *
     * @ORM\Column(name="lokalPlanLink", type="text", nullable=false)
     */
    private $lokalplanlink;

    /**
     * @var string
     *
     * @ORM\Column(name="samletAreal", type="text", nullable=false)
     */
    private $samletareal;

    /**
     * @var string
     *
     * @ORM\Column(name="salgbartAreal", type="text", nullable=false)
     */
    private $salgbartareal;

    /**
     * @var string
     *
     * @ORM\Column(name="forbrugsAndel", type="text", nullable=false)
     */
    private $forbrugsandel;

    /**
     * @var string
     *
     * @ORM\Column(name="createdBy", type="text", nullable=false)
     */
    private $createdby;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdDate", type="date", nullable=false)
     */
    private $createddate;

    /**
     * @var string
     *
     * @ORM\Column(name="modifiedBy", type="text", nullable=false)
     */
    private $modifiedby;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modifiedDate", type="date", nullable=false)
     */
    private $modifieddate;



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nr
     *
     * @param string $nr
     *
     * @return Lokalplan
     */
    public function setNr($nr)
    {
        $this->nr = $nr;

        return $this;
    }

    /**
     * Get nr
     *
     * @return string
     */
    public function getNr()
    {
        return $this->nr;
    }

    /**
     * Set lsnr
     *
     * @param string $lsnr
     *
     * @return Lokalplan
     */
    public function setLsnr($lsnr)
    {
        $this->lsnr = $lsnr;

        return $this;
    }

    /**
     * Get lsnr
     *
     * @return string
     */
    public function getLsnr()
    {
        return $this->lsnr;
    }

    /**
     * Set titel
     *
     * @param string $titel
     *
     * @return Lokalplan
     */
    public function setTitel($titel)
    {
        $this->titel = $titel;

        return $this;
    }

    /**
     * Get titel
     *
     * @return string
     */
    public function getTitel()
    {
        return $this->titel;
    }

    /**
     * Set projektleder
     *
     * @param string $projektleder
     *
     * @return Lokalplan
     */
    public function setProjektleder($projektleder)
    {
        $this->projektleder = $projektleder;

        return $this;
    }

    /**
     * Get projektleder
     *
     * @return string
     */
    public function getProjektleder()
    {
        return $this->projektleder;
    }

    /**
     * Set telefon
     *
     * @param string $telefon
     *
     * @return Lokalplan
     */
    public function setTelefon($telefon)
    {
        $this->telefon = $telefon;

        return $this;
    }

    /**
     * Get telefon
     *
     * @return string
     */
    public function getTelefon()
    {
        return $this->telefon;
    }

    /**
     * Set lokalplanlink
     *
     * @param string $lokalplanlink
     *
     * @return Lokalplan
     */
    public function setLokalplanlink($lokalplanlink)
    {
        $this->lokalplanlink = $lokalplanlink;

        return $this;
    }

    /**
     * Get lokalplanlink
     *
     * @return string
     */
    public function getLokalplanlink()
    {
        return $this->lokalplanlink;
    }

    /**
     * Set samletareal
     *
     * @param string $samletareal
     *
     * @return Lokalplan
     */
    public function setSamletareal($samletareal)
    {
        $this->samletareal = $samletareal;

        return $this;
    }

    /**
     * Get samletareal
     *
     * @return string
     */
    public function getSamletareal()
    {
        return $this->samletareal;
    }

    /**
     * Set salgbartareal
     *
     * @param string $salgbartareal
     *
     * @return Lokalplan
     */
    public function setSalgbartareal($salgbartareal)
    {
        $this->salgbartareal = $salgbartareal;

        return $this;
    }

    /**
     * Get salgbartareal
     *
     * @return string
     */
    public function getSalgbartareal()
    {
        return $this->salgbartareal;
    }

    /**
     * Set forbrugsandel
     *
     * @param string $forbrugsandel
     *
     * @return Lokalplan
     */
    public function setForbrugsandel($forbrugsandel)
    {
        $this->forbrugsandel = $forbrugsandel;

        return $this;
    }

    /**
     * Get forbrugsandel
     *
     * @return string
     */
    public function getForbrugsandel()
    {
        return $this->forbrugsandel;
    }

    /**
     * Set createdby
     *
     * @param string $createdby
     *
     * @return Lokalplan
     */
    public function setCreatedby($createdby)
    {
        $this->createdby = $createdby;

        return $this;
    }

    /**
     * Get createdby
     *
     * @return string
     */
    public function getCreatedby()
    {
        return $this->createdby;
    }

    /**
     * Set createddate
     *
     * @param \DateTime $createddate
     *
     * @return Lokalplan
     */
    public function setCreateddate($createddate)
    {
        $this->createddate = $createddate;

        return $this;
    }

    /**
     * Get createddate
     *
     * @return \DateTime
     */
    public function getCreateddate()
    {
        return $this->createddate;
    }

    /**
     * Set modifiedby
     *
     * @param string $modifiedby
     *
     * @return Lokalplan
     */
    public function setModifiedby($modifiedby)
    {
        $this->modifiedby = $modifiedby;

        return $this;
    }

    /**
     * Get modifiedby
     *
     * @return string
     */
    public function getModifiedby()
    {
        return $this->modifiedby;
    }

    /**
     * Set modifieddate
     *
     * @param \DateTime $modifieddate
     *
     * @return Lokalplan
     */
    public function setModifieddate($modifieddate)
    {
        $this->modifieddate = $modifieddate;

        return $this;
    }

    /**
     * Get modifieddate
     *
     * @return \DateTime
     */
    public function getModifieddate()
    {
        return $this->modifieddate;
    }
public function __toString() { return $this->titel; }}
