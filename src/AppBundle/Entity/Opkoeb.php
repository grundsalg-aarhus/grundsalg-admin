<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Opkoeb
 *
 * @ORM\Table(name="Opkoeb", indexes={@ORM\Index(name="fk_Opkoeb_lpId", columns={"lpId"})})
 * @ORM\Entity
 */
class Opkoeb
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
     * @ORM\Column(name="matrik1", type="string", length=50, nullable=true)
     */
    private $matrik1;

    /**
     * @var string
     *
     * @ORM\Column(name="matrik2", type="string", length=50, nullable=true)
     */
    private $matrik2;

    /**
     * @var string
     *
     * @ORM\Column(name="ejerlav", type="text", nullable=true)
     */
    private $ejerlav;

    /**
     * @var string
     *
     * @ORM\Column(name="m2", type="string", length=50, nullable=true)
     */
    private $m2;

    /**
     * @var string
     *
     * @ORM\Column(name="bemaerkning", type="text", nullable=true)
     */
    private $bemaerkning;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="opkoebDato", type="date", nullable=true)
     */
    private $opkoebdato;

    /**
     * @var string
     *
     * @ORM\Column(name="pris", type="string", length=50, nullable=true)
     */
    private $pris;

    /**
     * @var string
     *
     * @ORM\Column(name="procentAfLP", type="string", length=50, nullable=true)
     */
    private $procentaflp;

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
     * @var \Lokalplan
     *
     * @ORM\ManyToOne(targetEntity="Lokalplan")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="lpId", referencedColumnName="id")
     * })
     */
    private $lpid;



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
     * Set matrik1
     *
     * @param string $matrik1
     *
     * @return Opkoeb
     */
    public function setMatrik1($matrik1)
    {
        $this->matrik1 = $matrik1;

        return $this;
    }

    /**
     * Get matrik1
     *
     * @return string
     */
    public function getMatrik1()
    {
        return $this->matrik1;
    }

    /**
     * Set matrik2
     *
     * @param string $matrik2
     *
     * @return Opkoeb
     */
    public function setMatrik2($matrik2)
    {
        $this->matrik2 = $matrik2;

        return $this;
    }

    /**
     * Get matrik2
     *
     * @return string
     */
    public function getMatrik2()
    {
        return $this->matrik2;
    }

    /**
     * Set ejerlav
     *
     * @param string $ejerlav
     *
     * @return Opkoeb
     */
    public function setEjerlav($ejerlav)
    {
        $this->ejerlav = $ejerlav;

        return $this;
    }

    /**
     * Get ejerlav
     *
     * @return string
     */
    public function getEjerlav()
    {
        return $this->ejerlav;
    }

    /**
     * Set m2
     *
     * @param string $m2
     *
     * @return Opkoeb
     */
    public function setM2($m2)
    {
        $this->m2 = $m2;

        return $this;
    }

    /**
     * Get m2
     *
     * @return string
     */
    public function getM2()
    {
        return $this->m2;
    }

    /**
     * Set bemaerkning
     *
     * @param string $bemaerkning
     *
     * @return Opkoeb
     */
    public function setBemaerkning($bemaerkning)
    {
        $this->bemaerkning = $bemaerkning;

        return $this;
    }

    /**
     * Get bemaerkning
     *
     * @return string
     */
    public function getBemaerkning()
    {
        return $this->bemaerkning;
    }

    /**
     * Set opkoebdato
     *
     * @param \DateTime $opkoebdato
     *
     * @return Opkoeb
     */
    public function setOpkoebdato($opkoebdato)
    {
        $this->opkoebdato = $opkoebdato;

        return $this;
    }

    /**
     * Get opkoebdato
     *
     * @return \DateTime
     */
    public function getOpkoebdato()
    {
        return $this->opkoebdato;
    }

    /**
     * Set pris
     *
     * @param string $pris
     *
     * @return Opkoeb
     */
    public function setPris($pris)
    {
        $this->pris = $pris;

        return $this;
    }

    /**
     * Get pris
     *
     * @return string
     */
    public function getPris()
    {
        return $this->pris;
    }

    /**
     * Set procentaflp
     *
     * @param string $procentaflp
     *
     * @return Opkoeb
     */
    public function setProcentaflp($procentaflp)
    {
        $this->procentaflp = $procentaflp;

        return $this;
    }

    /**
     * Get procentaflp
     *
     * @return string
     */
    public function getProcentaflp()
    {
        return $this->procentaflp;
    }

    /**
     * Set createdby
     *
     * @param string $createdby
     *
     * @return Opkoeb
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
     * @return Opkoeb
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
     * @return Opkoeb
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
     * @return Opkoeb
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

    /**
     * Set lpid
     *
     * @param \AppBundle\Entity\Lokalplan $lpid
     *
     * @return Opkoeb
     */
    public function setLpid(\AppBundle\Entity\Lokalplan $lpid = null)
    {
        $this->lpid = $lpid;

        return $this;
    }

    /**
     * Get lpid
     *
     * @return \AppBundle\Entity\Lokalplan
     */
    public function getLpid()
    {
        return $this->lpid;
    }
public function __toString() { return __CLASS__; }}
