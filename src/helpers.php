<?php
const PAYMENTS = ['dinheiro' => '💵 Dinheiro', 'pix' => '⚡ Pix', 'cartao' => '💳 Cartão'];

const STATUS_PREPARING = 'Preparando para entrega';
const STATUS_DELIVERED = 'Entregue';

function fetch_orders(PDO $pdo, $userId = null) {
    $st = $pdo->prepare("
        SELECT o.id, o.payment, o.total, o.status, o.created_at, u.name, u.address,
               GROUP_CONCAT(CONCAT(oi.qty, '× ', c.name) ORDER BY c.name SEPARATOR ', ') AS items
        FROM orders o
        JOIN users u ON u.id = o.user_id
        JOIN order_items oi ON oi.order_id = o.id
        JOIN cupcakes c ON c.id = oi.cupcake_id
        WHERE ? IS NULL OR o.user_id = ?
        GROUP BY o.id
        ORDER BY o.id DESC
    ");
    $st->execute([$userId, $userId]);
    return $st->fetchAll();
}

function status_badge($status) {
    return '<span class="badge' . ($status === STATUS_DELIVERED ? ' done' : '') . '">' . e($status) . '</span>';
}

function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function money($v) { return 'R$ ' . number_format($v, 2, ',', '.'); }

function header_html($title) { require __DIR__ . '/../views/header.php'; }
function footer_html($scripts = []) { require __DIR__ . '/../views/footer.php'; }
