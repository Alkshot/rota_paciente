<?php
declare(strict_types=1);

function e(?string $valor): string
{
    return htmlspecialchars($valor ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function json_response(array $dados, int $status = 200): never
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($dados, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function json_erro(string $mensagem, int $status = 400, array $extra = []): never
{
    json_response(['ok' => false, 'erro' => $mensagem] + $extra, $status);
}

function ler_entrada(): array
{
    $tipo = $_SERVER['CONTENT_TYPE'] ?? '';
    if (str_contains($tipo, 'application/json')) {
        $dados = json_decode(file_get_contents('php://input') ?: '', true);
        return is_array($dados) ? $dados : [];
    }
    return $_POST;
}

function exigir_metodo(string $metodo): void
{
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== strtoupper($metodo)) {
        json_erro('Método não permitido', 405);
    }
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_campo(): string
{
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

function csrf_validar(?string $token): bool
{
    return is_string($token)
        && !empty($_SESSION['csrf'])
        && hash_equals($_SESSION['csrf'], $token);
}

function exigir_csrf(array $entrada): void
{
    $token = $entrada['csrf'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? null);
    if (!csrf_validar($token)) {
        json_erro('Token CSRF inválido', 403);
    }
}