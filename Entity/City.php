<?php 

// City entity

namespace Jul\LyfyBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Jul\LyfyBundle\Entity\Repository\CityRepository")
 * 
 * @author julien
 *
 */
class City
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 */
	private $id;
	
	/**
	 * @ORM\Column(length=128)
	 * @Assert\NotBlank(message="Please select a city")
	 */
	private $name;
	
	/**
	 * @ORM\Column(length=255, unique=true)
	 * @Assert\NotBlank
	 */
	private $fullname;
	
	/**
	 * @Gedmo\Slug(fields={"fullname"}, style="camel")
	 * @ORM\Column(length=255, nullable=true)
	 */
	private $slug;
	
	/**
	 * @ORM\ManyToOne(targetEntity="State")
	 */
	private $state;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Country")
	 */
	private $country;
	
	/**
	 * @ORM\Column(type="float")
	 */
	private $latitude;
	
	/**
	 * @ORM\Column(type="float")
	 */
	private $longitude;
	
	
	// ------------------------------------------------------
	
    
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
     * @return City
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
     * @return City
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
     * @return City
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
     * Set state
     *
     * @param Jul\LyfyBundle\Entity\State $state
     * @return City
     */
    public function setState(\Jul\LyfyBundle\Entity\State $state = null)
    {
    	$this->state = $state;
    
    	return $this;
    }
    
    /**
     * Get state
     *
     * @return Jul\LyfyBundle\Entity\State
     */
    public function getState()
    {
    	return $this->state;
    }
    
    /**
     * Set country
     *
     * @param Jul\LyfyBundle\Entity\Country $country
     * @return City
     */
    public function setCountry(\Jul\LyfyBundle\Entity\Country $country = null)
    {
        $this->country = $country;
    
        return $this;
    }

    /**
     * Get country
     *
     * @return Jul\LyfyBundle\Entity\Country 
     */
    public function getCountry()
    {
        return $this->country;
    }
	
    /**
     * Set latitude
     *
     * @param float $latitude
     * @return City
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
     * @return City
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
}