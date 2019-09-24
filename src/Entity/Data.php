<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DataRepository")
 */
class Data
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gatewayEui;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $profileId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $endpointId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $clusterId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $attributeId;

    /**
     * @ORM\Column(type="integer")
     */
    private $value;

    /**
     * @ORM\Column(type="integer")
     */
    private $timestamp;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGatewayEui(): ?string
    {
        return $this->gatewayEui;
    }

    public function setGatewayEui(string $gatewayEui): self
    {
        $this->gatewayEui = $gatewayEui;

        return $this;
    }

    public function getProfileId(): ?string
    {
        return $this->profileId;
    }

    public function setProfileId(string $profileId): self
    {
        $this->profileId = $profileId;

        return $this;
    }

    public function getEndpointId(): ?string
    {
        return $this->endpointId;
    }

    public function setEndpointId(string $endpointId): self
    {
        $this->endpointId = $endpointId;

        return $this;
    }

    public function getClusterId(): ?string
    {
        return $this->clusterId;
    }

    public function setClusterId(string $clusterId): self
    {
        $this->clusterId = $clusterId;

        return $this;
    }

    public function getAttributeId(): ?string
    {
        return $this->attributeId;
    }

    public function setAttributeId(string $attributeId): self
    {
        $this->attributeId = $attributeId;

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getTimestamp(): ?int
    {
        return $this->timestamp;
    }

    public function setTimestamp(int $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }
}
