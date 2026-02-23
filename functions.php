<?php
session_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('PRODUCTS_FILE', 'products.json');
define('DATA_DIR', __DIR__ . '/data');
define('USERS_FILE', DATA_DIR . '/users.json');
define('PRODUCTS_FILE', DATA_DIR . '/products.json');
define('SUPPLIERS_FILE', DATA_DIR . '/suppliers.json');
define('INVOICES_FILE', DATA_DIR . '/invoices.json');
define('DRAFT_DIR', DATA_DIR . '/drafts');

// Load products from JSON (always)
function getProducts() {
    if (!file_exists(PRODUCTS_FILE)) {
        file_put_contents(PRODUCTS_FILE, json_encode([]));
function ensureDataStore(): void {
    if (!is_dir(DATA_DIR)) {
        mkdir(DATA_DIR, 0777, true);
    }
    $json = file_get_contents(PRODUCTS_FILE);
    return json_decode($json, true);
}

// Save products to JSON
function saveProducts($products) {
    file_put_contents(PRODUCTS_FILE, json_encode($products, JSON_PRETTY_PRINT));
    if (!is_dir(DRAFT_DIR)) {
        mkdir(DRAFT_DIR, 0777, true);
    }

    ensureFile(USERS_FILE, [
        [
            'id' => 1,
            'username' => 'admin',
            'password' => password_hash('admin123', PASSWORD_DEFAULT),
            'role' => 'manager'
        ],
        [
            'id' => 2,
            'username' => 'cashier',
            'password' => password_hash('cashier123', PASSWORD_DEFAULT),
            'role' => 'cashier'
        ]
    ]);

    ensureFile(SUPPLIERS_FILE, [
        ['id' => 1, 'name' => 'Bean House Co.', 'phone' => '012 555 888', 'address' => 'Phnom Penh'],
        ['id' => 2, 'name' => 'Milk & Sugar Supply', 'phone' => '015 777 222', 'address' => 'Siem Reap']
    ]);

    ensureFile(PRODUCTS_FILE, [
        ['id' => 1, 'name' => 'Espresso', 'price' => 2.00, 'stock' => 120, 'supplier_id' => 1],
        ['id' => 2, 'name' => 'Latte', 'price' => 2.80, 'stock' => 90, 'supplier_id' => 1],
        ['id' => 3, 'name' => 'Iced Americano', 'price' => 2.40, 'stock' => 70, 'supplier_id' => 1],
        ['id' => 4, 'name' => 'Chocolate Cake', 'price' => 3.20, 'stock' => 35, 'supplier_id' => 2]
    ]);

    ensureFile(INVOICES_FILE, []);
}

// Add product
function addProduct($name, $price) {
    $products = getProducts();
    $id = empty($products) ? 1 : end($products)['id'] + 1;
    $products[] = ['id'=>$id, 'name'=>$name, 'price'=>$price];
    saveProducts($products);
}

// Cart functions (optional, can use session)
function addToCart($product_id, $qty) {
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $qty;
    } else {
        $_SESSION['cart'][$product_id] = $qty;
function ensureFile(string $file, array $seed): void {
    if (!file_exists($file)) {
        file_put_contents($file, json_encode($seed, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}

function getCartProducts() {
    $cartItems = [];
    if (!isset($_SESSION['cart'])) return $cartItems;
    $products = getProducts();
    foreach ($_SESSION['cart'] as $pid => $qty) {
        foreach ($products as $p) {
            if ($p['id'] == $pid) {
                $p['qty'] = $qty;
                $p['total'] = $p['price'] * $qty;
                $cartItems[] = $p;
                break;
            }
        }
function loadData(string $file): array {
    ensureDataStore();
    $raw = file_get_contents($file);
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

function saveData(string $file, array $data): void {
    ensureDataStore();
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function nextId(array $rows): int {
    if (empty($rows)) {
        return 1;
    }
    return $cartItems;
    return max(array_column($rows, 'id')) + 1;
}

// Login system
$users = [
    'NSM'     => password_hash('NSM@admin', PASSWORD_DEFAULT),
    'cashier' => password_hash('admin@123', PASSWORD_DEFAULT),
    'risa'    => password_hash('admin2323', PASSWORD_DEFAULT)
];
function getUsers(): array { return loadData(USERS_FILE); }
function getProducts(): array { return loadData(PRODUCTS_FILE); }
function getSuppliers(): array { return loadData(SUPPLIERS_FILE); }
function getInvoices(): array { return loadData(INVOICES_FILE); }

function saveProducts(array $rows): void { saveData(PRODUCTS_FILE, $rows); }
function saveSuppliers(array $rows): void { saveData(SUPPLIERS_FILE, $rows); }
function saveInvoices(array $rows): void { saveData(INVOICES_FILE, $rows); }

function login($username, $password) {
    global $users;
    if (isset($users[$username]) && password_verify($password, $users[$username])) {
        $_SESSION['user'] = $username;
        return true;
function login(string $username, string $password): bool {
    foreach (getUsers() as $user) {
        if ($user['username'] === $username && password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role']
            ];
            return true;
        }
    }
    return false;
}

function isLoggedIn() {
function isLoggedIn(): bool {
    return isset($_SESSION['user']);
}

function logout() {
function logout(): void {
    $_SESSION = [];
    session_destroy();
}
?>

function getCurrentUser(): ?array {
    return $_SESSION['user'] ?? null;
}

function cartInit(): void {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
}

function getDraftFile(string $username): string {
    return DRAFT_DIR . '/draft_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $username) . '.json';
}

function saveDraftCart(string $username, array $cart): void {
    file_put_contents(getDraftFile($username), json_encode($cart, JSON_PRETTY_PRINT));
}

function restoreDraftCart(string $username): void {
    $file = getDraftFile($username);
    if (empty($_SESSION['cart']) && file_exists($file)) {
        $data = json_decode(file_get_contents($file), true);
        if (is_array($data)) {
            $_SESSION['cart'] = $data;
        }
    }
}

function clearDraftCart(string $username): void {
    $file = getDraftFile($username);
    if (file_exists($file)) {
        unlink($file);
    }
}

function formatMoney(float $amount): string {
    return '$' . number_format($amount, 2);
}

function dashboardReport(): array {
    $invoices = getInvoices();
    $products = getProducts();

    $today = date('Y-m-d');
    $todaySales = 0.0;
    $totalSales = 0.0;
    $soldByProduct = [];

    foreach ($invoices as $inv) {
        $totalSales += (float)$inv['grand_total'];
        if (($inv['date'] ?? '') === $today) {
            $todaySales += (float)$inv['grand_total'];
        }
        foreach (($inv['items'] ?? []) as $item) {
            $soldByProduct[$item['name']] = ($soldByProduct[$item['name']] ?? 0) + (int)$item['qty'];
        }
    }

    arsort($soldByProduct);

    return [
        'total_sales' => $totalSales,
        'today_sales' => $todaySales,
        'invoice_count' => count($invoices),
        'low_stock' => array_values(array_filter($products, fn($p) => (int)$p['stock'] <= 10)),
        'top_products' => array_slice($soldByProduct, 0, 5, true)
    ];
}

ensureDataStore();
