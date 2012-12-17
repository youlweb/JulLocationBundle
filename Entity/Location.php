<?php 

// Location entity

namespace Jul\LocationBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Jul\LocationBundle\Entity\Repository\LocationRepository")
 * @ORM\Table(indexes={@ORM\Index(name="search_idx", columns={"name", "postcode"})})
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
	 * @ORM\Column(length=128, nullable=true)
	 * @Assert\NotBlank(groups={"LocationName","LocationNameCode","LocationNoCode","LocationFull"})
	 */
	private $name;
	
	/**
	 * @ORM\Column(unique=true, nullable=true)
	 * @Assert\NotBlank(groups={"LocationFullnameCode","LocationNoCode","LocationFull"})
	 */
	private $fullname;
	
	/**
	 * @Gedmo\Slug(fields={"fullname"}, style="camel")
	 * @ORM\Column(nullable=true)
	 */
	private $slug;
	
	/**
	 * @ORM\Column(nullable=true)
	 * @Assert\NotBlank(groups={"LocationFull"})
	 */
	private $address;
	
	/**
	 * @ORM\Column(nullable=true)
	 * @Assert\NotBlank(groups={"LocationAddress", "LocationFull"})
	 */
	private $fulladdress;
	
	/**
	 * @ORM\Column(length=10, nullable=true)
	 * @Assert\NotBlank(groups={"LocationNameCode","LocationFullnameCode","LocationCode","LocationFull"})
	 */
	private $postcode;
	
	/**
	 * @ORM\ManyToOne(targetEntity="City")
	 * @ORM\JoinColumn(onDelete="set null")
	 */
	private $city;
	
	/**
	 * @ORM\Column(type="float", nullable=true)
	 * @Assert\NotBlank(groups={"LocationNoCode","LocationFull"})
	 */
	private $latitude;
	
	/**
	 * @ORM\Column(type="float", nullable=true)
	 * @Assert\NotBlank(groups={"LocationNoCode","LocationFull"})
	 */
	private $longitude;
	
	/**
	 * @ORM\Column(nullable=true)
	 */
	private $imagePath;
	
	/**
	 * @ORM\Column(nullable=true)
	 */
	private $website;
	
	/**
	 * @ORM\Column(length=64, nullable=true)
	 */
	private $phone;
	
	
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
     * Set postcode
     *
     * @param string $postcode
     * @return Location
     */
    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;
    
        return $this;
    }

    /**
     * Get postcode
     *
     * @return string 
     */
    public function getPostcode()
    {
        return $this->postcode;
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

    /**
     * Set fulladdress
     *
     * @param string $fulladdress
     * @return Location
     */
    public function setFulladdress($fulladdress)
    {
        $this->fulladdress = $fulladdress;
    
        return $this;
    }

    /**
     * Get fulladdress
     *
     * @return string 
     */
    public function getFulladdress()
    {
        return $this->fulladdress;
    }

    /**
     * Set imagePath
     *
     * @param string $imagePath
     * @return Location
     */
    public function setImagePath($imagePath)
    {
        $this->imagePath = $imagePath;
    
        return $this;
    }

    /**
     * Get imagePath
     *
     * @return string 
     */
    public function getImagePath()
    {
        return $this->imagePath;
    }

    /**
     * Set website
     *
     * @param string $website
     * @return Location
     */
    public function setWebsite($website)
    {
        $this->website = $website;
    
        return $this;
    }

    /**
     * Get website
     *
     * @return string 
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Location
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    
        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }
}