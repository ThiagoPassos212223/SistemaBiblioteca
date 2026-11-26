<?php

// Esta classe representa um convvidado registrado no sistema.
// Ela guarda identificador, nome e status do convite.
// Os métodos permitem consultar e alterar essas informações.
class Convidado implements JsonSerializable {
    private ?int $id;
    private string $nome;
    private bool $statusConvite;

    public function __construct(?int $id, string $nome, bool $statusConvite = false) {
        $this->id = $id;
        $this->nome = $nome;
        $this->statusConvite = $statusConvite;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function setId(?int $id): void {
        $this->id = $id;
    }

    public function getNome(): string {
        return $this->nome;
    }

    public function setNome(string $nome): void {
        $this->nome = $nome;
    }

    public function getStatusConvite(): bool {
        return $this->statusConvite;
    }

    public function setStatusConvite(bool $statusConvite): void {
        $this->statusConvite = $statusConvite;
    }

    public function jsonSerialize(): array {
        return [
            'idCONVIDADO' => $this->id,
            'nome' => $this->nome,
            'status_convite' => $this->statusConvite ? 1 : 0
        ];
    }
}
