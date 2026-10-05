<?php
declare(strict_types=1);

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $path, array|callable $handler, array $middlewares = []): void
    {
        $this->addRoute('GET', $path, $handler, $middlewares);
    }

    public function post(string $path, array|callable $handler, array $middlewares = []): void
    {
        $this->addRoute('POST', $path, $handler, $middlewares);
    }

    private function addRoute(string $method, string $path, array|callable $handler, array $middlewares = []): void
    {
        $pattern = preg_replace('~\{([a-zA-Z0-9_]+)\}~', '(?P<$1>[^/]+)', $path);
        $pattern = '#^' . $pattern . '$#';

        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'pattern' => $pattern,
            'handler' => $handler,
            'middlewares' => $middlewares,
        ];
    }

    public function dispatch(string $uri, string $method): void
    {
        $parsedUrl = parse_url($uri);
        $path = '/' . trim($parsedUrl['path'] ?? '/', '/');
        if ($path !== '/' && str_ends_with($path, '/')) {
            $path = rtrim($path, '/');
        }

        $method = strtoupper($method);

        // Check for _method override in POST (e.g. DELETE, PUT)
        if ($method === 'POST' && isset($_POST['_method'])) {
            $method = strtoupper($_POST['_method']);
        }

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            if (preg_match($route['pattern'], $path, $matches)) {
                $params = [];
                foreach ($matches as $k => $v) {
                    if (is_string($k)) {
                        $params[$k] = urldecode($v);
                    }
                }

                // Run middlewares
                foreach ($route['middlewares'] as $middleware) {
                    $this->runMiddleware($middleware);
                }

                $this->executeHandler($route['handler'], $params);
                return;
            }
        }

        // No route matched -> 404
        $this->handleNotFound($path);
    }

    private function runMiddleware(string $middleware): void
    {
        if ($middleware === 'csrf') {
            if (!Csrf::validate()) {
                if (str_starts_with($_SERVER['REQUEST_URI'] ?? '', '/api/')) {
                    json_response(['success' => false, 'message' => 'Invalid or expired CSRF token.'], 403);
                }
                flash('error', 'Session expired. Please try again.');
                redirect($_SERVER['HTTP_REFERER'] ?? '/');
            }
        }

        if ($middleware === 'auth:admin') {
            Session::start();
            $adminId = Session::get('admin_user_id');
            if (empty($adminId)) {
                if (str_starts_with($_SERVER['REQUEST_URI'] ?? '', '/api/')) {
                    json_response(['success' => false, 'message' => 'Unauthorized admin access.'], 401);
                }
                Session::set('admin_redirect_after_login', $_SERVER['REQUEST_URI'] ?? '/admin');
                redirect('/admin/login');
            }

            // Optional IP allowlist check
            $allowlist = config('admin.ip_allowlist', []);
            if (!empty($allowlist)) {
                $clientIp = $_SERVER['REMOTE_ADDR'] ?? '';
                if (!in_array($clientIp, $allowlist, true)) {
                    http_response_code(403);
                    echo "Access denied: IP ($clientIp) is not in admin allowlist.";
                    exit;
                }
            }
        }
    }

    private function executeHandler(array|callable $handler, array $params): void
    {
        if (is_callable($handler)) {
            call_user_func_array($handler, array_values($params));
            return;
        }

        if (is_array($handler) && count($handler) === 2) {
            [$class, $method] = $handler;
            if (is_string($class)) {
                $controller = new $class();
            } else {
                $controller = $class;
            }
            call_user_func_array([$controller, $method], array_values($params));
            return;
        }

        throw new \Exception("Invalid route handler.");
    }

    private function handleNotFound(string $path): void
    {
        http_response_code(404);

        if (str_starts_with($path, '/api/')) {
            json_response(['success' => false, 'error' => 'API endpoint not found: ' . $path], 404);
        }

        View::setMeta([
            'title' => '404 Scent Not Found — Luxury Club',
            'description' => 'The luxury fragrance page you are looking for does not exist or has drifted away.'
        ]);

        View::render('pages/404', ['path' => $path]);
        exit;
    }
}
