<?php

// Esta classe representa uma divulgaçao realizada pela biblioteca.
// Ela guarda data, endereço e identificação do convidado.
// Os métodos permitem consultar e modificar os dados.
class Divulgacao implements JsonSerializable {
    private ?int $id;
    private ?int $idConvidado;
    private string $data;
    private string $endereco;

    public function __construct(?int $id, string $data, string $endereco, ?int $idConvidado = null) {
        $this->id = $id;
        $this->data = $data;
        $this->endereco = $endereco;
        $this->idConvidado = $idConvidado;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function setId(?int $id): void {
        $this->id = $id;
    }

    public function getIdConvidado(): ?int {
        return $this->idConvidado;
    }

    public function setIdConvidado(?int $idConvidado): void {
        $this->idConvidado = $idConvidado;
    }

    public function getData(): string {
        return $this->data;
    }

    public function setData(string $data): void {
        $this->data = $data;
    }

    public function getEndereco(): string {
        return $this->endereco;
    }

    public function setEndereco(string $endereco): void {
        $this->endereco = $endereco;
    }

    public function jsonSerialize(): array {
        return [
            'idDIVULGACAO' => $this->id,
            'idCONVIDADO' => $this->idConvidado,
            'data' => $this->data,
            'endereco' => $this->endereco
        ];
    }
}
