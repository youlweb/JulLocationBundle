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

abstract class City
{
	/**
	 * @var integer
	 */
	protected $id;
	
	/**
	 * @var string
	 * @Assert\NotBlank(groups={"city_name"})
	 */
	protected $name;
	
	/**
	 * @var string
	 * @Assert\NotBlank(groups={"city_long_name"})
	 */
	protected $long_name;
	
	/**
	 * @var string
	 */
	protected $slug;
	
	/**
	 * @var float
	 * @Assert\NotBlank(groups={"city_latitude"})
	 */
	protected $latitude;
	
	/**
	 * @var float
	 * @Assert\NotBlank(groups={"city_longitude"})
	 */
	protected $longitude;
	
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
	 * Set long_name
	 *
	 * @param string $longName
	 * @return City
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