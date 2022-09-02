<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\RequestRepository;

#[ORM\Entity(repositoryClass: RequestRepository::class)]
class Request implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $lang_from = null;

    #[ORM\Column(length: 255)]
    private ?string $lang_to = null;

    #[ORM\Column(length: 2048)]
    private ?string $search = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datetime = null;

    #[ORM\Column(length: 4096, nullable: true)]
    private ?string $result = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLangFrom(): ?string
    {
        return $this->lang_from;
    }

    public function setLangFrom(string $lang_from): self
    {
        $this->lang_from = $lang_from;

        return $this;
    }

    public function getLangTo(): ?string
    {
        return $this->lang_to;
    }

    public function setLangTo(string $lang_to): self
    {
        $this->lang_to = $lang_to;

        return $this;
    }

    public function getSearch(): ?string
    {
        return $this->search;
    }

    public function setSearch(string $search): self
    {
        $this->search = $search;

        return $this;
    }

    public function getDatetime(): ?\DateTimeInterface
    {
        return $this->datetime;
    }

    public function setDatetime(\DateTimeInterface $datetime): self
    {
        $this->datetime = $datetime;

        return $this;
    }

    public function getResult(): ?string
    {
        return $this->result;
    }

    public function setResult(?string $result): self
    {
        $this->result = $result;

        return $this;
    }

    public function jsonSerialize()
    {
        return array(
            'id' => $this->id,
            'lang_from'=> $this->lang_from,
            'search'=> $this->search,
            'lang_to'=> $this->lang_to,
            'result'=> $this->result,
            'datetime'=> $this->datetime,
        );
    }
}
