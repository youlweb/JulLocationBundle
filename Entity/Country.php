<?php 

namespace Jul\LocationBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * JulLocationBundle Country entity
 * 
 * @ORM\Entity
 * @ORM\Table(indexes={@ORM\Index(name="search_idx", columns={"name"})})
 * 
 * @author julien
 *
 */
class Country
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 */
	private $id;
	
	/**
	 * @ORM\Column(length=128, nullable=true)
	 * @Assert\NotBlank(groups={"countryname"})
	 */
	private $name;
	
	/**
	 * @Gedmo\Slug(fields={"name"}, style="camel")
	 * @ORM\Column(length=128, nullable=true)
	 */
	private $slug;
	
	/**
	 * @ORM\Column(length=128, nullable=true)
	 * @Assert\NotBlank(groups={"countryshortname"})
	 */
	private $shortname;
	
	/**
	 * @ORM\Column(type="float", nullable=true)
	 * @Assert\NotBlank(groups={"countrylatitude"})
	 */
	private $latitude;
	
	/**
	 * @ORM\Column(type="float", nullable=true)
	 * @Assert\NotBlank(groups={"countrylongitude"})
	 */
	private $longitude;
	
	
	// -------------------------------------------
	
	
	/**
	 * Return string value
	 *
	 * @return string
	 */
	public function __toString()
	{
		return "cac";
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
     * @return Country
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
     * Set slug
     *
     * @param string $slug
     * @return Country
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
     * @return Country
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
     * @return Country
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
     * @return Country
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