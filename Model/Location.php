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

abstract class Location
{
	/**
	 * @var integer
	 */
	protected $id;
	
	/**
	 * @var string
	 * @Assert\NotBlank(groups={"location_name"})
	 */
	protected $name;
	
	/**
	 * @var string
	 * @Assert\NotBlank(groups={"location_long_name"})
	 */
	protected $long_name;
	
	/**
	 * @var string
	 */
	protected $slug;
	
	/**
	 * @var string
	 * @Assert\NotBlank(groups={"location_address"})
	 */
	protected $address;
	
	/**
	 * @var string
	 * @Assert\NotBlank(groups={"location_long_address"})
	 */
	protected $long_address;
	
	/**
	 * @var string
	 * @Assert\NotBlank(groups={"location_postcode"})
	 */
	protected $postcode;
	
	/**
	 * @var float
	 * @Assert\NotBlank(groups={"location_latitude"})
	 */
	protected $latitude;
	
	/**
	 * @var float
	 * @Assert\NotBlank(groups={"location_longitude"})
	 */
	protected $longitude;
	
	/**
	 * @var string
	 * @Assert\NotBlank(groups={"location_image_url"})
	 */
	protected $image_url;
	
	/**
	 * @var string
	 * @Assert\NotBlank(groups={"location_website_url"})
	 */
	protected $website_url;
	
	/**
	 * @var string
	 * @Assert\NotBlank(groups={"location_phone"})
	 */
	protected $phone;
	
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
	 * Set long_name
	 *
	 * @param string $longName
	 * @return Location
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
	 * Set long_address
	 *
	 * @param string $longAddress
	 * @return Location
	 */
	public function setLongAddress($longAddress)
	{
		$this->long_address = $longAddress;
	
		return $this;
	}
	
	/**
	 * Get long_address
	 *
	 * @return string
	 */
	public function getLongAddress()
	{
		return $this->long_address;
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
	 * Set image_url
	 *
	 * @param string $imageUrl
	 * @return Location
	 */
	public function setImageUrl($imageUrl)
	{
		$this->image_url = $imageUrl;
	
		return $this;
	}
	
	/**
	 * Get image_url
	 *
	 * @return string
	 */
	public function getImageUrl()
	{
		return $this->image_url;
	}
	
	/**
	 * Set website_url
	 *
	 * @param string $websiteUrl
	 * @return Location
	 */
	public function setWebsiteUrl($websiteUrl)
	{
		$this->website_url = $websiteUrl;
	
		return $this;
	}
	
	/**
	 * Get website_url
	 *
	 * @return string
	 */
	public function getWebsiteUrl()
	{
		return $this->website_url;
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