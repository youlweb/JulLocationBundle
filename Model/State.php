<?php 

/*
 * JulLocationBundle Symfony package.
 *
 * © 2013 Julien Tord <http://github.com/youlweb/JulLocationBundle>
 *
 * Full license information in the LICENSE text file distributed
 * with this source code.
 *
 */

namespace Jul\LocationBundle\Model;

use Symfony\Component\Validator\Constraints as Assert;

abstract class State
{
	/**
	 * @var integer
	 */
	protected $id;
	
	/**
	 * @var string
	 * @Assert\NotBlank(groups={"state_name"})
	 */
	protected $name;
	
	/**
	 * @var string
	 * @Assert\NotBlank(groups={"state_long_name"})
	 */
	protected $long_name;
	
	/**
	 * @var string
	 * @Assert\NotBlank(groups={"state_short_name"})
	 */
	protected $short_name;
	
	/**
	 * @var string
	 */
	protected $slug;
	
	/**
	 * @var float
	 * @Assert\NotBlank(groups={"state_latitude"})
	 */
	protected $latitude;
	
	/**
	 * @var float
	 * @Assert\NotBlank(groups={"state_longitude"})
	 */
	protected $longitude;
	
	/**
	 * @var Country
	 */
	protected $country;
	
	/*
	 * --------------------------------------------------------
	 */
	
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
	 * Set long_name
	 *
	 * @param string $longName
	 * @return State
	 */
	public function setLongName($longName)
	{
		$this->long_name = $longName;
	
		return $this;
	}
	
	/**
	 * Get long_name
	 *
	 * @return string
	 */
	public function getLongName()
	{
		return $this->long_name;
	}
	
	/**
	 * Set short_name
	 *
	 * @param string $shortName
	 * @return State
	 */
	public function setShortName($shortName)
	{
		$this->short_name = $shortName;
	
		return $this;
	}
	
	/**
	 * Get short_name
	 *
	 * @return string
	 */
	public function getShortName()
	{
		return $this->short_name;
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
	 * @param Country|null $country
	 * @return State
	 */
	public function setCountry( $country = null)
	{
		$this->country = $country;
	
		return $this;
	}
	
	/**
	 * Get country
	 *
	 * @return Country|null
	 */
	public function getCountry()
	{
		return $this->country;
	}
	
	/**
	 * Return string value
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->getName();
	}
}