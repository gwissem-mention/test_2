<?php

namespace App\Oodrive\ParamsObject;

class SearchParamObject
{
    private ?string $q = null;

    /**
     * @var array<string>|null
     */
    private ?array $in = null;

    private ?string $folderId = null;

    private ?\DateTimeImmutable $after = null;

    private ?\DateTimeImmutable $before = null;

    /**
     * @var array<string>|null
     */
    private ?array $type = null;

    private ?string $sortBy = null;

    private ?string $sortOrder = null;

    private ?int $count = null;

    private ?string $position = null;

    /**
     * @var array<string>|null
     */
    private ?array $metadata = null;

    public function q(?string $q): self
    {
        $this->q = $q;

        return $this;
    }

    /**
     * @param array<string>|null $in
     */
    public function in(?array $in): self
    {
        $this->in = $in;

        return $this;
    }

    public function folderId(?string $folderId): self
    {
        $this->folderId = $folderId;

        return $this;
    }

    public function after(?\DateTimeImmutable $after): self
    {
        $this->after = $after;

        return $this;
    }

    public function before(?\DateTimeImmutable $before): self
    {
        $this->before = $before;

        return $this;
    }

    /**
     * @param array<string>|null $type
     */
    public function type(?array $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function sortBy(?string $sortBy): self
    {
        $this->sortBy = $sortBy;

        return $this;
    }

    public function sortOrder(?string $sortOrder): self
    {
        $this->sortOrder = $sortOrder;

        return $this;
    }

    public function count(?int $count): self
    {
        $this->count = $count;

        return $this;
    }

    public function position(?string $position): self
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @param array<string>|null $metadata
     */
    public function metadata(?array $metadata): self
    {
        $this->metadata = $metadata;

        return $this;
    }

    public function asUrlPart(): string
    {
        return http_build_query($this->asArray());
    }

    /**
     * @return array<string, mixed>
     */
    public function asArray(): array
    {
        $arr = [
            'q' => $this->q,
            'in' => $this->in,
            'folderId' => $this->folderId,
            'after' => $this->after?->format(\DateTimeInterface::ATOM),
            'before' => $this->before?->format(\DateTimeInterface::ATOM),
            'type' => $this->type,
            'sortBy' => $this->sortBy,
            'sortOrder' => $this->sortOrder,
            'count' => $this->count,
            'position' => $this->position,
            'metadata' => $this->metadata,
        ];

        return array_filter($arr, static fn ($value) => null !== $value);
    }
}
