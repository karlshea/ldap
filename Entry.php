<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Ldap;

/**
 * @author Charles Sarrazin <charles@sarraz.in>
 */
class Entry
{
    private $dn;
    private $attributes;
    private $lowercaseAttributes;

    public function __construct(string $dn, array $attributes = [])
    {
        $this->dn = $dn;
        $this->attributes = $attributes;
        $this->lowercaseAttributes = array_change_key_case($attributes);
    }

    /**
     * Returns the entry's DN.
     *
     * @return string
     */
    public function getDn()
    {
        return $this->dn;
    }

    /**
     * Returns whether an attribute exists.
     *
     * @param string $name The name of the attribute
     * @param bool $forceLowercase Force lowercase attribute check
     *
     * @return bool
     */
    public function hasAttribute(string $name, bool $forceLowercase = false)
    {
        return $forceLowercase ? isset($this->lowercaseAttributes[mb_strtolower($name)]) : isset($this->attributes[$name]);
    }

    /**
     * Returns a specific attribute's value.
     *
     * As LDAP can return multiple values for a single attribute,
     * this value is returned as an array.
     *
     * @param string $name The name of the attribute
     * @param bool $forceLowercase Use the lowercase name of the attribute
     *
     * @return array|null
     */
    public function getAttribute(string $name, bool $forceLowercase = false)
    {
        return $forceLowercase 
            ? (isset($this->lowercaseAttributes[mb_strtolower($name)]) ? $this->lowercaseAttributes[mb_strtolower($name)] : null)
            : (isset($this->attributes[$name]) ? $this->attributes[$name] : null);
    }

    /**
     * Returns the complete list of attributes.
     *
     * @param bool $forceLowercase Get the lowercase list of attributes
     *
     * @return array
     */
    public function getAttributes(bool $forceLowercase = false)
    {
        return $forceLowercase ? $this->lowercaseAttributes : $this->attributes;
    }

    /**
     * Sets a value for the given attribute.
     */
    public function setAttribute(string $name, array $value)
    {
        $this->attributes[$name] = $value;
        $this->lowercaseAttributes[mb_strtolower($name)] = $value;
    }

    /**
     * Removes a given attribute.
     */
    public function removeAttribute(string $name)
    {
        unset($this->attributes[$name]);
        unset($this->lowercaseAttributes[mb_strtolower($name)]);
    }
}
