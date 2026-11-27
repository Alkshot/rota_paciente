<?php
declare(strict_types=1);

function normalizar_telefone_br(string $telefone): ?string
{
    $n = preg_replace('/\D/', '', $telefone) ?? '';
    if (str_starts_with($n, '55') && strlen($n) >= 12) {
        $n = substr($n, 2);
    }
    if (!preg_match('/^\d{10,11}$/', $n)) {
        return null;
    }
    return '55' . $n;
}

function link_whatsapp(string $telefone, string $mensagem): ?string
{
    $n = normalizar_telefone_br($telefone);
    return $n === null ? null : 'https://wa.me/' . $n . '?text=' . rawurlencode($mensagem);
}

function url_acompanhamento(string $codigo): string
{
    $esquema = !empty($_SERVER['HTTPS']) ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    return "$esquema://$host/acompanhar.php?codigo=" . rawurlencode($codigo);
}

function mensagem_agendamento(string $primeiroNome, string $especialidade, DateTimeInterface $quando, string $link): string
{
    return sprintf(
        'Olá, %s. Sua consulta de %s foi agendada para %s às %s. Acompanhe: %s',
        $primeiroNome,
        $especialidade,
        $quando->format('d/m'),
        $quando->format('H\hi'),
        $link
    );
}