<?php
declare(strict_types=1);

const PERFIS = ['UBS', 'ESPECIALIZADA', 'HOSPITAL', 'GESTOR'];

function iniciar_sessao_segura(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'httponly' => true,
        'samesite' => 'Lax',
        'secure'   => !empty($_SERVER['HTTPS']),
    ]);
    session_start();
}

function registrar_login(array $usuario): void
{
    if (!in_array($usuario['perfil'], PERFIS, true)) {
        throw new InvalidArgumentException('Perfil inválido');
    }
    session_regenerate_id(true);
    $_SESSION['usuario'] = [
        'id'         => (int) $usuario['id'],
        'nome'       => (string) $usuario['nome'],
        'perfil'     => $usuario['perfil'],
        'unidade_id' => (int) $usuario['unidade_id'],
    ];
}

function fazer_logout(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}

function usuario_logado(): ?array
{
    return $_SESSION['usuario'] ?? null;
}

function home_do_perfil(string $perfil): string
{
    return match ($perfil) {
        'UBS'           => '/ubs/index.php',
        'ESPECIALIZADA' => '/especializada/index.php',
        'HOSPITAL'      => '/hospital/index.php',
        'GESTOR'        => '/gestor/index.php',
        default         => '/login.php',
    };
}

function exigir_perfil(array $permitidos): array
{
    $u = usuario_logado();
    if ($u === null) {
        header('Location: /rota_paciente/frontend/index.html');
        exit;
    }
    if (!in_array($u['perfil'], $permitidos, true)) {
        http_response_code(403);
        exit('Acesso negado');
    }
    return $u;
}

function exigir_perfil_api(array $permitidos): array
{
    $u = usuario_logado();
    if ($u === null) {
        json_erro('Não autenticado', 401);
    }
    if (!in_array($u['perfil'], $permitidos, true)) {
        json_erro('Acesso negado para este perfil', 403);
    }
    return $u;
}

function email_demo_do_perfil(string $perfil): ?string
{
    return in_array($perfil, PERFIS, true) ? strtolower($perfil) . '@demo.local' : null;
}