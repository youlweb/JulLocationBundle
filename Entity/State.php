<?php 

// State entity

namespace Jul\LocationBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Jul\LocationBundle\Entity\Repository\StateRepository")
 * @ORM\Table(indexes={@ORM\Index(name="search_idx", columns={"name", "code"})})
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
	 * @ORM\Column(length=128)
	 */
	private $name;
	
	/**
	 * @Gedmo\Slug(fields={"name"}, style="camel", unique=false)
	 * @ORM\Column(length=128, nullable=true)
	 */
	private $slug;
	
	/**
	 * @ORM\Column(length=2)
	 */
	private $code;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Country")
	 */
	private $country;
	
	
	// ---------------------------------------
	
	
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
     * Set code
     *
     * @param string $code
     * @return Country
     */
    public function setCode($code)
    {
        $this->code = $code;
    
        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set country
     *
     * @param Jul\LocationBundle\Entity\Country $country
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
     * @return Jul\LocationBundle\Entity\Country 
     */
    public function getCountry()
    {
        return $this->country;
    }
}