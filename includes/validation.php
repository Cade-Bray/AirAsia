<?php
// ========================================================
// Air Asia Mileage Rewards Program - Validation Logic
// File: includes/validation.php
// Centralized server-side validation functions
// ========================================================

if (!function_exists('getValidCardTypes')) {
    /**
     * Returns the allowed Gift Card categories based on business requirements.
     */
    function getValidCardTypes(): array {
        return [
            'Travel',
            'Shopping',
            'Electronics',
            'Food & Dining',
            'Food & Beverage',
            'Transportation',
            'Travel & Lodging',
            'Entertainment',
            'Retail'
        ];
    }
}

if (!function_exists('validateCardId')) {
    /**
     * Validates that a Gift Card identifier is a valid positive integer.
     *
     * @param mixed $id Raw input from $_GET or $_POST
     * @return int|null Valid integer ID, or null if invalid/missing
     */
    function validateCardId($id): ?int {
        if ($id === null || $id === '') {
            return null;
        }
        $filtered = filter_var($id, FILTER_VALIDATE_INT);
        return ($filtered !== false && $filtered > 0) ? (int)$filtered : null;
    }
}

if (!function_exists('validateCardName')) {
    /**
     * Validates that Card Name is present and within a reasonable length.
     */
    function validateCardName(?string $name): ?string {
        $trimmed = trim($name ?? '');
        if ($trimmed === '') {
            return 'Card Name is required.';
        }
        if (strlen($trimmed) > 100) {
            return 'Card Name cannot exceed 100 characters.';
        }
        return null;
    }
}

if (!function_exists('validateCardType')) {
    /**
     * Validates that Card Type is an allowed value from the business categories.
     */
    function validateCardType(?string $type): ?string {
        $trimmed = trim($type ?? '');
        if ($trimmed === '') {
            return 'Please select a Card Type.';
        }
        if (!in_array($trimmed, getValidCardTypes(), true)) {
            return 'Invalid Card Type selected.';
        }
        return null;
    }
}

if (!function_exists('validatePoints')) {
    /**
     * Validates that Points Required is a positive whole number.
     */
    function validatePoints($points): ?string {
        $trimmed = trim((string)($points ?? ''));
        if ($trimmed === '') {
            return 'Cost to redeem (Points Required) is required.';
        }
        if (!filter_var($trimmed, FILTER_VALIDATE_INT) || (int)$trimmed < 1) {
            return 'Points must be a positive integer greater than 0.';
        }
        return null;
    }
}

if (!function_exists('validateCardValue')) {
    /**
     * Validates that Card Value is a positive number.
     */
    function validateCardValue($value): ?string {
        $trimmed = trim((string)($value ?? ''));
        if ($trimmed === '') {
            return 'Card Value is required.';
        }
        if (!is_numeric($trimmed) || (float)$trimmed <= 0) {
            return 'Card Value must be a valid dollar amount greater than $0.00.';
        }
        return null;
    }
}

if (!function_exists('validateGiftCardData')) {
    /**
     * Generalized validation for Gift Card attributes before inserting or updating.
     * Shared between card-add.php and card-update.php.
     *
     * @param array $input Raw $_POST input values
     * @param array|null $file Optional $_FILES['cardImage'] upload array
     * @return array Contains 'errors' (array of error messages) and 'data' (sanitized values)
     */
    function validateGiftCardData(array $input, ?array $file = null): array {
        $errors = [];
        $data = [];

        // 1. Card Name validation
        $nameError = validateCardName($input['cardName'] ?? null);
        if ($nameError !== null) {
            $errors['cardName'] = $nameError;
        } else {
            $data['cardName'] = trim($input['cardName']);
        }

        // 2. Card Type validation
        $typeError = validateCardType($input['cardType'] ?? null);
        if ($typeError !== null) {
            $errors['cardType'] = $typeError;
        } else {
            $data['cardType'] = trim($input['cardType']);
        }

        // 3. Points Required validation
        $pointsError = validatePoints($input['points'] ?? null);
        if ($pointsError !== null) {
            $errors['points'] = $pointsError;
        } else {
            $data['points'] = (int)trim($input['points']);
        }

        // 4. Card Monetary Value validation
        $valueError = validateCardValue($input['cardValue'] ?? null);
        if ($valueError !== null) {
            $errors['cardValue'] = $valueError;
        } else {
            $data['cardValue'] = (float)trim($input['cardValue']);
        }

        // 5. Optional Image File validation
        if ($file && isset($file['error']) && $file['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($file['error'] !== UPLOAD_ERR_OK) {
                $errors['cardImage'] = 'Error uploading image. Please try again.';
            } elseif ($file['size'] > 5 * 1024 * 1024) {
                $errors['cardImage'] = 'Gift card image must be 5MB or smaller.';
            }
        }

        return [
            'errors' => $errors,
            'data'   => $data
        ];
    }
}

if (!function_exists('renderValidationAlert')) {
    /**
     * Renders a Bootstrap alert banner summarizing all validation errors.
     */
    function renderValidationAlert(array $errors): string {
        if (empty($errors)) {
            return '';
        }
        $html = '<div class="alert alert-danger shadow-sm mb-4" role="alert">';
        $html .= '<div class="d-flex align-items-center mb-2">';
        $html .= '<i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>';
        $html .= '<span class="fw-bold">Please correct the following errors:</span>';
        $html .= '</div>';
        $html .= '<ul class="mb-0 ps-3">';
        foreach ($errors as $error) {
            $html .= '<li>' . htmlspecialchars($error) . '</li>';
        }
        $html .= '</ul>';
        $html .= '</div>';
        return $html;
    }
}

if (!function_exists('getFieldValidationClass')) {
    /**
     * Returns 'is-invalid' if the field has an error, otherwise empty string.
     */
    function getFieldValidationClass(array $errors, string $field): string {
        return isset($errors[$field]) ? 'is-invalid' : '';
    }
}

if (!function_exists('renderFieldFeedback')) {
    /**
     * Renders inline invalid-feedback HTML if the field has an error.
     */
    function renderFieldFeedback(array $errors, string $field): string {
        if (!isset($errors[$field])) {
            return '';
        }
        return '<div class="invalid-feedback">' . htmlspecialchars($errors[$field]) . '</div>';
    }
}
