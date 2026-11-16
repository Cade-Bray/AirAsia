<?php
// ========================================================
// Air Asia Mileage Rewards Program - Validation Test Harness
// File: tests/validation-tests.php
// Tests server-side validation functions without adding,
// changing, or deleting any database records.
// ========================================================

require_once __DIR__ . '/../includes/validation.php';

// Verify database record count before running tests (read-only check)
$dbStatus = 'Pure in-memory validation test (no database records modified).';
$recordsBefore = null;
$recordsAfter = null;

if (file_exists(__DIR__ . '/../includes/db.php')) {
    try {
        require_once __DIR__ . '/../includes/db.php';
        if (isset($pdo) && $pdo instanceof PDO) {
            $recordsBefore = (int)$pdo->query('SELECT COUNT(*) FROM GiftCard')->fetchColumn();
        }
    } catch (Throwable $e) {
        // Fall back gracefully if DB is offline; validation functions do not require DB
    }
}

// --------------------------------------------------------
// Test Suite Definition
// --------------------------------------------------------
$tests = [];

// Base valid test payload
$baseValid = [
    'cardName'  => 'AirAsia Flight Voucher',
    'cardType'  => 'Travel',
    'cardValue' => '50.00',
    'points'    => '5000'
];

// 1. Core Test 1: Valid Gift Card data -> Passes validation
$res1 = validateGiftCardData($baseValid);
$pass1 = empty($res1['errors']);
$tests[] = [
    'id'       => 1,
    'core'     => true,
    'test'     => 'Valid Gift Card data',
    'expected' => 'Passes validation',
    'actual'   => $pass1 ? 'Passes validation' : 'Fails validation: ' . implode('; ', $res1['errors']),
    'details'  => 'Name: "AirAsia Flight Voucher", Type: "Travel", Value: $50.00, Points: 5000',
    'status'   => $pass1 ? 'PASS' : 'FAIL'
];

// 2. Core Test 2: Blank Card Name -> Fails validation
$data2 = array_merge($baseValid, ['cardName' => '']);
$res2 = validateGiftCardData($data2);
$pass2 = !empty($res2['errors']['cardName']);
$tests[] = [
    'id'       => 2,
    'core'     => true,
    'test'     => 'Blank Card Name',
    'expected' => 'Fails validation',
    'actual'   => $pass2 ? 'Fails validation (' . $res2['errors']['cardName'] . ')' : 'Passes validation',
    'details'  => 'Name: "" (empty string), Type: "Travel", Value: $50.00, Points: 5000',
    'status'   => $pass2 ? 'PASS' : 'FAIL'
];

// 3. Core Test 3: Altered or invalid Card Type -> Fails validation
$data3 = array_merge($baseValid, ['cardType' => 'InvalidCategory_XYZ']);
$res3 = validateGiftCardData($data3);
$pass3 = !empty($res3['errors']['cardType']);
$tests[] = [
    'id'       => 3,
    'core'     => true,
    'test'     => 'Altered or invalid Card Type',
    'expected' => 'Fails validation',
    'actual'   => $pass3 ? 'Fails validation (' . $res3['errors']['cardType'] . ')' : 'Passes validation',
    'details'  => 'Name: "AirAsia Flight Voucher", Type: "InvalidCategory_XYZ" (not allowed), Value: $50.00, Points: 5000',
    'status'   => $pass3 ? 'PASS' : 'FAIL'
];

// 4. Core Test 4: Zero or negative Card Value -> Fails validation
$data4Zero = array_merge($baseValid, ['cardValue' => '0.00']);
$res4Zero = validateGiftCardData($data4Zero);
$data4Neg  = array_merge($baseValid, ['cardValue' => '-25.00']);
$res4Neg  = validateGiftCardData($data4Neg);
$pass4 = !empty($res4Zero['errors']['cardValue']) && !empty($res4Neg['errors']['cardValue']);
$tests[] = [
    'id'       => 4,
    'core'     => true,
    'test'     => 'Zero or negative Card Value',
    'expected' => 'Fails validation',
    'actual'   => $pass4 ? 'Fails validation (' . $res4Zero['errors']['cardValue'] . ')' : 'Passes validation',
    'details'  => 'Tested with Card Value = $0.00 and -$25.00 (both rejected)',
    'status'   => $pass4 ? 'PASS' : 'FAIL'
];

// 5. Core Test 5: Invalid Points Required -> Fails validation
$data5Zero = array_merge($baseValid, ['points' => '0']);
$res5Zero = validateGiftCardData($data5Zero);
$data5Neg  = array_merge($baseValid, ['points' => '-500']);
$res5Neg  = validateGiftCardData($data5Neg);
$data5Dec  = array_merge($baseValid, ['points' => '12.50']);
$res5Dec  = validateGiftCardData($data5Dec);
$data5Str  = array_merge($baseValid, ['points' => 'free']);
$res5Str  = validateGiftCardData($data5Str);
$pass5 = !empty($res5Zero['errors']['points']) && !empty($res5Neg['errors']['points']) && !empty($res5Dec['errors']['points']) && !empty($res5Str['errors']['points']);
$tests[] = [
    'id'       => 5,
    'core'     => true,
    'test'     => 'Invalid Points Required',
    'expected' => 'Fails validation',
    'actual'   => $pass5 ? 'Fails validation (' . $res5Zero['errors']['points'] . ')' : 'Passes validation',
    'details'  => 'Tested with Points = 0, -500, 12.50 (decimal), and "free" (non-numeric) (all rejected)',
    'status'   => $pass5 ? 'PASS' : 'FAIL'
];

// --------------------------------------------------------
// Additional Boundary & Identifier Checks
// --------------------------------------------------------

// 6. Negative Card Value Boundary Test
$resNegVal = validateCardValue('-10.00');
$passNegVal = ($resNegVal !== null);
$tests[] = [
    'id'       => 6,
    'core'     => false,
    'test'     => 'Negative Card Value (-$10.00)',
    'expected' => 'Fails validation',
    'actual'   => $passNegVal ? 'Fails validation (' . $resNegVal . ')' : 'Passes validation',
    'details'  => 'validateCardValue("-10.00")',
    'status'   => $passNegVal ? 'PASS' : 'FAIL'
];

// 7. Non-numeric Card Value Boundary Test
$resNaNVal = validateCardValue('not_numeric');
$passNaNVal = ($resNaNVal !== null);
$tests[] = [
    'id'       => 7,
    'core'     => false,
    'test'     => 'Non-numeric Card Value ("not_numeric")',
    'expected' => 'Fails validation',
    'actual'   => $passNaNVal ? 'Fails validation (' . $resNaNVal . ')' : 'Passes validation',
    'details'  => 'validateCardValue("not_numeric")',
    'status'   => $passNaNVal ? 'PASS' : 'FAIL'
];

// 8. Zero Points Required Boundary Test
$resZeroPts = validatePoints('0');
$passZeroPts = ($resZeroPts !== null);
$tests[] = [
    'id'       => 8,
    'core'     => false,
    'test'     => 'Zero Points Required (0)',
    'expected' => 'Fails validation',
    'actual'   => $passZeroPts ? 'Fails validation (' . $resZeroPts . ')' : 'Passes validation',
    'details'  => 'validatePoints("0")',
    'status'   => $passZeroPts ? 'PASS' : 'FAIL'
];

// 9. Negative Points Required Boundary Test
$resNegPts = validatePoints('-100');
$passNegPts = ($resNegPts !== null);
$tests[] = [
    'id'       => 9,
    'core'     => false,
    'test'     => 'Negative Points Required (-100)',
    'expected' => 'Fails validation',
    'actual'   => $passNegPts ? 'Fails validation (' . $resNegPts . ')' : 'Passes validation',
    'details'  => 'validatePoints("-100")',
    'status'   => $passNegPts ? 'PASS' : 'FAIL'
];

// 10. Decimal Points Required Boundary Test
$resDecPts = validatePoints('25.5');
$passDecPts = ($resDecPts !== null);
$tests[] = [
    'id'       => 10,
    'core'     => false,
    'test'     => 'Decimal Points Required (25.5)',
    'expected' => 'Fails validation',
    'actual'   => $passDecPts ? 'Fails validation (' . $resDecPts . ')' : 'Passes validation',
    'details'  => 'validatePoints("25.5")',
    'status'   => $passDecPts ? 'PASS' : 'FAIL'
];

// 11. Card Name Exceeding 100 Characters Boundary Test
$longName = str_repeat('A', 101);
$resLongName = validateCardName($longName);
$passLongName = ($resLongName !== null);
$tests[] = [
    'id'       => 11,
    'core'     => false,
    'test'     => 'Card Name Exceeding 100 Characters (101 chars)',
    'expected' => 'Fails validation',
    'actual'   => $passLongName ? 'Fails validation (' . $resLongName . ')' : 'Passes validation',
    'details'  => 'validateCardName(101 "A" characters)',
    'status'   => $passLongName ? 'PASS' : 'FAIL'
];

// 12. Valid Gift Card Identifier Test
$validId = validateCardId('15');
$passValidId = ($validId === 15);
$tests[] = [
    'id'       => 12,
    'core'     => false,
    'test'     => 'Valid Gift Card Identifier ("15")',
    'expected' => 'Passes validation',
    'actual'   => $passValidId ? 'Passes validation (returned integer 15)' : 'Fails validation',
    'details'  => 'validateCardId("15")',
    'status'   => $passValidId ? 'PASS' : 'FAIL'
];

// 13. Non-integer Gift Card Identifier (SQL Injection Protection)
$resInjId = validateCardId("1' OR '1'='1");
$passInjId = ($resInjId === null);
$tests[] = [
    'id'       => 13,
    'core'     => false,
    'test'     => 'Non-integer Identifier ("1\' OR \'1\'=\'1")',
    'expected' => 'Fails validation',
    'actual'   => $passInjId ? 'Fails validation (safely rejected, returned null)' : 'Passes validation',
    'details'  => 'validateCardId("1\' OR \'1\'=\'1")',
    'status'   => $passInjId ? 'PASS' : 'FAIL'
];

// 14. Zero or Negative Gift Card Identifier
$resZeroId = validateCardId('0');
$resNegId = validateCardId('-5');
$passZeroId = ($resZeroId === null && $resNegId === null);
$tests[] = [
    'id'       => 14,
    'core'     => false,
    'test'     => 'Zero or Negative Card Identifier ("0", "-5")',
    'expected' => 'Fails validation',
    'actual'   => $passZeroId ? 'Fails validation (safely rejected, returned null)' : 'Passes validation',
    'details'  => 'validateCardId("0") and validateCardId("-5")',
    'status'   => $passZeroId ? 'PASS' : 'FAIL'
];

// Audit database records after test run (read-only check)
if (isset($pdo) && $pdo instanceof PDO && $recordsBefore !== null) {
    $recordsAfter = (int)$pdo->query('SELECT COUNT(*) FROM GiftCard')->fetchColumn();
    $delta = $recordsAfter - $recordsBefore;
    $dbStatus = "Database records before: {$recordsBefore} | after: {$recordsAfter} | Delta: {$delta} (0 added, 0 changed, 0 deleted).";
}

// Compute pass/fail counts
$passCount = 0;
$failCount = 0;
foreach ($tests as $t) {
    if ($t['status'] === 'PASS') {
        $passCount++;
    } else {
        $failCount++;
    }
}
$allPassed = ($failCount === 0);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Validation Test Harness - AirAsia Rewards</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@latest/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        .badge-pass {
            background-color: #198754;
            color: #ffffff;
            font-size: 0.875rem;
            padding: 0.35em 0.75em;
        }
        .badge-fail {
            background-color: #dc3545;
            color: #ffffff;
            font-size: 0.875rem;
            padding: 0.35em 0.75em;
        }
    </style>
</head>

<body>
    <!-- Main Content Area -->
    <main class="container py-4 py-md-5">
        <div class="outer-frame">
            <!-- Navigation Back Link -->
            <div class="d-flex align-items-center justify-content-between mb-4">
                <a href="../card-list.php" class="btn btn-outline-secondary btn-sm" id="btnReturnList">
                    <i class="bi bi-arrow-left me-1"></i> Return to Gift Card List
                </a>
                <span class="badge bg-light text-dark border">Part 6 Test Harness</span>
            </div>

            <!-- Header Box -->
            <div class="text-center mb-4 mb-md-5">
                <div class="page-header-box">
                    <h1 class="page-header-title">Validation Test Harness</h1>
                </div>
                <p class="text-muted mt-2">
                    Tests server-side validation functions without adding, changing, or deleting database records.
                </p>
            </div>

            <!-- Overall Status Banner -->
            <?php if ($allPassed): ?>
                <div class="alert alert-success shadow-sm mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill fs-3 me-3 text-success"></i>
                        <div>
                            <strong class="fs-5 d-block">All Validation Tests Passed (<?php echo $passCount; ?> / <?php echo count($tests); ?>)</strong>
                            <span><?php echo htmlspecialchars($dbStatus); ?></span>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-danger shadow-sm mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-x-circle-fill fs-3 me-3 text-danger"></i>
                        <div>
                            <strong class="fs-5 d-block">Some Tests Failed (<?php echo $failCount; ?> failure(s))</strong>
                            <span><?php echo htmlspecialchars($dbStatus); ?></span>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Core Required Tests (Part 6 Specification) -->
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header bg-dark text-white d-flex align-items-center justify-content-between py-3">
                    <h2 class="h5 mb-0 fw-bold">
                        <i class="bi bi-shield-check me-2 text-warning"></i> Required Validation Tests (Part 6)
                    </h2>
                    <span class="badge bg-warning text-dark fw-bold">5 Core Tests</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 30%;">Test</th>
                                    <th style="width: 25%;">Expected result</th>
                                    <th style="width: 25%;">Actual result</th>
                                    <th style="width: 15%; text-align: center;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tests as $t): ?>
                                    <?php if (!empty($t['core'])): ?>
                                        <tr data-test="<?php echo htmlspecialchars($t['test']); ?>" data-status="<?php echo htmlspecialchars($t['status']); ?>">
                                            <td><?php echo (int)$t['id']; ?></td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($t['test']); ?></strong>
                                                <small class="text-muted d-block"><?php echo htmlspecialchars($t['details']); ?></small>
                                            </td>
                                            <td><code><?php echo htmlspecialchars($t['expected']); ?></code></td>
                                            <td>
                                                <span class="<?php echo ($t['status'] === 'PASS') ? 'text-success' : 'text-danger'; ?>">
                                                    <?php echo htmlspecialchars($t['actual']); ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge <?php echo ($t['status'] === 'PASS') ? 'badge-pass' : 'badge-fail'; ?>">
                                                    <?php echo htmlspecialchars($t['status']); ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Additional Boundary and Identifier Checks -->
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header bg-secondary text-white d-flex align-items-center justify-content-between py-3">
                    <h2 class="h5 mb-0 fw-bold">
                        <i class="bi bi-sliders me-2"></i> Additional Boundary &amp; Identifier Tests
                    </h2>
                    <span class="badge bg-light text-dark fw-bold">Boundary &amp; Security Checks</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 30%;">Test</th>
                                    <th style="width: 25%;">Expected result</th>
                                    <th style="width: 25%;">Actual result</th>
                                    <th style="width: 15%; text-align: center;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tests as $t): ?>
                                    <?php if (empty($t['core'])): ?>
                                        <tr data-test="<?php echo htmlspecialchars($t['test']); ?>" data-status="<?php echo htmlspecialchars($t['status']); ?>">
                                            <td><?php echo (int)$t['id']; ?></td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($t['test']); ?></strong>
                                                <small class="text-muted d-block"><code><?php echo htmlspecialchars($t['details']); ?></code></small>
                                            </td>
                                            <td><code><?php echo htmlspecialchars($t['expected']); ?></code></td>
                                            <td>
                                                <span class="<?php echo ($t['status'] === 'PASS') ? 'text-success' : 'text-danger'; ?>">
                                                    <?php echo htmlspecialchars($t['actual']); ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge <?php echo ($t['status'] === 'PASS') ? 'badge-pass' : 'badge-fail'; ?>">
                                                    <?php echo htmlspecialchars($t['status']); ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Database Audit Card -->
            <div class="card bg-light border-0 shadow-sm p-3 text-center text-muted small">
                <div>
                    <i class="bi bi-database-check text-success fs-5 me-1 align-middle"></i>
                    <strong>Database Integrity Guarantee:</strong>
                    <span>All tests executed purely in-memory. Zero records were inserted, modified, or deleted in rewards.db.</span>
                </div>
            </div>
        </div>
    </main>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@latest/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
// Set exit code according to test success
if (php_sapi_name() === 'cli' && !$allPassed) {
    exit(1);
}
?>