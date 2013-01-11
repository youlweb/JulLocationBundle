<?php 

namespace Jul\LocationBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * JulLocationBundle State entity
 * 
 * @ORM\Entity(repositoryClass="Jul\LocationBundle\Entity\Repository\StateRepository")
 * @ORM\Table(indexes={@ORM\Index(name="search_idx", columns={"name", "fullname"})})
 * 
 * @author julien
 *
 */
class State
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 */
	private $id;
	
	/**
	 * @ORM\Column(length=128, nullable=true)
	 * @Assert\NotBlank(groups={"statename"})
	 */
	private $name;
	
	/**
	 * @ORM\Column(nullable=true)
	 * @Assert\NotBlank(groups={"statefullname"})
	 */
	private $fullname;
	
	/**
	 * @Gedmo\Slug(fields={"fullname"}, style="camel", unique=false)
	 * @ORM\Column(length=128, nullable=true)
	 */
	private $slug;
	
	/**
	 * @ORM\Column(length=128, nullable=true)
	 * @Assert\NotBlank(groups={"stateshortname"})
	 */
	private $shortname;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Country")
	 * @ORM\JoinColumn(onDelete="set null")
	 */
	private $country;
	
	/**
	 * @ORM\Column(type="float", nullable=true)
	 * @Assert\NotBlank(groups={"statelatitude"})
	 */
	private $latitude;
	
	/**
	 * @ORM\Column(type="float", nullable=true)
	 * @Assert\NotBlank(groups={"statelongitude"})
	 */
	private $longitude;
	
	
	// ---------------------------------------
	
	
	/**
	 * Return string value
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->getName();
	}

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
     * Set name
     *
     * @param string $name
     * @return State
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set fullname
     *
     * @param string $fullname
     * @return State
     */
    public function setFullname($fullname)
    {
        $this->fullname = $fullname;
    
        return $this;
    }

    /**
     * Get fullname
     *
     * @return string 
     */
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return State
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    
        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set shortname
     *
     * @param string $shortname
     * @return State
     */
    public function setShortname($shortname)
    {
        $this->shortname = $shortname;
    
        return $this;
    }

    /**
     * Get shortname
     *
     * @return string 
     */
    public function getShortname()
    {
        return $this->shortname;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     * @return State
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    
        return $this;
    }

    /**
     * Get latitude
     *
     * @return float 
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     * @return State
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    
        return $this;
    }

    /**
     * Get longitude
     *
     * @return float 
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set country
     *
     * @param \Jul\LocationBundle\Entity\Country $country
     * @return State
     */
    public function setCountry(\Jul\LocationBundle\Entity\Country $country = null)
    {
        $this->country = $country;
    
        return $this;
    }

    /**
     * Get country
     *
     * @return \Jul\LocationBundle\Entity\Country 
     */
    public function getCountry()
    {
        return $this->country;
    }
}