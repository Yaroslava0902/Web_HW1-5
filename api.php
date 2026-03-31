<?php
/**
 * api.php — REST-like endpoint
 * Обробляє:
 *   GET  api.php?action=snp_list          → список SNP із БД
 *   GET  api.php?action=snp_search&q=...  → пошук SNP
 *   POST api.php?action=contact           → збереження форми зворотного зв'язку
 *
 * Також є htmx-фрагменти (повертають HTML):
 *   GET  api.php?action=htmx_snp_table    → HTML-таблиця для htmx
 *   GET  api.php?action=htmx_snp_row&id= → один рядок таблиці
 */

require_once __DIR__ . '/db_config.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';

// ── CORS та preflight ─────────────────────────────────────
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, HX-Request');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }

// ── Роутер ────────────────────────────────────────────────
switch ($action) {

    // ── JSON: повний список SNP ───────────────────────────
    case 'snp_list':
        try {
            $pdo  = getDB();
            $stmt = $pdo->query(
                'SELECT id, rs_id, gene, chromosome, position,
                        ref_allele, alt_allele, maf, consequence, disease
                 FROM snp_variants ORDER BY chromosome, position'
            );
            jsonResponse(['status' => 'ok', 'data' => $stmt->fetchAll()]);
        } catch (PDOException $e) {
            jsonResponse(['status' => 'error', 'message' => $e->getMessage()], 500);
        }

    // ── JSON: пошук SNP ───────────────────────────────────
    case 'snp_search':
        $q = '%' . trim($_GET['q'] ?? '') . '%';
        try {
            $pdo  = getDB();
            $stmt = $pdo->prepare(
                'SELECT id, rs_id, gene, chromosome, position,
                        ref_allele, alt_allele, maf, consequence, disease
                 FROM snp_variants
                 WHERE rs_id LIKE ? OR gene LIKE ? OR disease LIKE ?
                 ORDER BY chromosome, position LIMIT 50'
            );
            $stmt->execute([$q, $q, $q]);
            jsonResponse(['status' => 'ok', 'data' => $stmt->fetchAll()]);
        } catch (PDOException $e) {
            jsonResponse(['status' => 'error', 'message' => $e->getMessage()], 500);
        }

    // ── AJAX POST: контактна форма ────────────────────────
    case 'contact':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            jsonResponse(['status' => 'error', 'message' => 'POST required'], 405);
        }

        // Зчитуємо з JSON або form-data
        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;

        $name    = trim($input['name']    ?? '');
        $email   = trim($input['email']   ?? '');
        $subject = trim($input['subject'] ?? '');
        $message = trim($input['message'] ?? '');

        // Валідація
        $errors = [];
        if (mb_strlen($name) < 2)   $errors[] = "Ім'я занадто коротке";
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Невалідна email-адреса';
        if (mb_strlen($message) < 10) $errors[] = 'Повідомлення занадто коротке';

        if ($errors) {
            jsonResponse(['status' => 'error', 'errors' => $errors], 422);
        }

        try {
            $pdo  = getDB();
            $stmt = $pdo->prepare(
                'INSERT INTO contact_requests (name, email, subject, message, ip_address)
                 VALUES (?, ?, ?, ?, ?)'
            );
            $stmt->execute([
                $name, $email, $subject, $message,
                $_SERVER['REMOTE_ADDR'] ?? null
            ]);
            jsonResponse(['status' => 'ok', 'message' => 'Дякуємо! Ваше повідомлення отримано.']);
        } catch (PDOException $e) {
            jsonResponse(['status' => 'error', 'message' => $e->getMessage()], 500);
        }

    // ── HTMX: HTML-фрагмент таблиці SNP ─────────────────
    case 'htmx_snp_table':
        header('Content-Type: text/html; charset=utf-8');
        $search = trim($_GET['search'] ?? '');
        try {
            $pdo = getDB();
            if ($search !== '') {
                $q    = "%$search%";
                $stmt = $pdo->prepare(
                    'SELECT id, rs_id, gene, chromosome, ref_allele, alt_allele,
                            maf, consequence, disease
                     FROM snp_variants
                     WHERE rs_id LIKE ? OR gene LIKE ? OR disease LIKE ?
                     ORDER BY chromosome LIMIT 20'
                );
                $stmt->execute([$q, $q, $q]);
            } else {
                $stmt = $pdo->query(
                    'SELECT id, rs_id, gene, chromosome, ref_allele, alt_allele,
                            maf, consequence, disease
                     FROM snp_variants ORDER BY chromosome LIMIT 20'
                );
            }
            $rows = $stmt->fetchAll();
        } catch (PDOException $e) {
            echo '<tr><td colspan="7" class="htmx-error">Помилка БД: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
            exit;
        }

        $consequenceColors = [
            'missense'   => '#60a5fa',
            'nonsense'   => '#f87171',
            'silent'     => '#4ade80',
            'splice'     => '#fb923c',
            'intergenic' => '#a78bfa',
            'regulatory' => '#facc15',
        ];

        if (empty($rows)) {
            echo '<tr><td colspan="7" style="text-align:center;color:#64748b;padding:2rem;font-family:monospace;">Нічого не знайдено</td></tr>';
            exit;
        }

        foreach ($rows as $r) {
            $color = $consequenceColors[$r['consequence']] ?? '#94a3b8';
            $mafPct = $r['maf'] !== null ? number_format($r['maf'] * 100, 2) . '%' : '—';
            printf(
                '<tr>
                  <td><code style="color:#60a5fa">%s</code></td>
                  <td><strong style="color:#fff">%s</strong></td>
                  <td style="text-align:center">%s</td>
                  <td style="text-align:center;font-family:monospace">
                    <span style="color:#f87171">%s</span> → <span style="color:#4ade80">%s</span>
                  </td>
                  <td style="text-align:center">%s</td>
                  <td><span style="color:%s;font-family:monospace;font-size:0.78rem">%s</span></td>
                  <td style="color:#94a3b8;font-size:0.85rem">%s</td>
                </tr>',
                htmlspecialchars($r['rs_id']),
                htmlspecialchars($r['gene']),
                (int)$r['chromosome'],
                htmlspecialchars($r['ref_allele']),
                htmlspecialchars($r['alt_allele']),
                $mafPct,
                $color,
                htmlspecialchars($r['consequence']),
                htmlspecialchars($r['disease'] ?? '—')
            );
        }
        exit;

    default:
        jsonResponse(['status' => 'error', 'message' => 'Unknown action'], 400);
}
