<?php 

// Location entity

namespace Jul\LocationBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Jul\LocationBundle\Entity\Repository\LocationRepository")
 * 
 * @author julien
 *
 */
class Location
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
	 * @ORM\Column(length=255, nullable=true)
	 */
	private $address;
	
	/**
	 * @ORM\ManyToOne(targetEntity="City")
	 */
	private $city;
	
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
     * @return Location
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
     * @return Location
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
     * @return Location
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
     * Set address
     *
     * @param string $address
     * @return Location
     */
    public function setAddress($address)
    {
        $this->address = $address;
    
        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     * @return Location
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
     * @return Location
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
     * Set city
     *
     * @param \Jul\LocationBundle\Entity\City $city
     * @return Location
     */
    public function setCity(\Jul\LocationBundle\Entity\City $city = null)
    {
        $this->city = $city;
    
        return $this;
    }

    /**
     * Get city
     *
     * @return \Jul\LocationBundle\Entity\City 
     */
    public function getCity()
    {
        return $this->city;
    }
}