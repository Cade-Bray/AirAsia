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

if (!function_exists('validateGiftCardData')) {
    /**
     * Generalized validation for Gift Card attributes.
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
        $cardName = trim($input['cardName'] ?? '');
        if ($cardName === '') {
            $errors['cardName'] = 'Card Name is required.';
        } elseif (strlen($cardName) > 100) {
            $errors['cardName'] = 'Card Name cannot exceed 100 characters.';
        } else {
            $data['cardName'] = $cardName;
        }

        // 2. Card Type validation
        $cardType = trim($input['cardType'] ?? '');
        $validTypes = getValidCardTypes();
        if ($cardType === '') {
            $errors['cardType'] = 'Please select a Card Type.';
        } elseif (!in_array($cardType, $validTypes, true)) {
            $errors['cardType'] = 'Invalid Card Type selected.';
        } else {
            $data['cardType'] = $cardType;
        }

        // 3. Points Required validation
        $pointsRaw = trim($input['points'] ?? '');
        if ($pointsRaw === '') {
            $errors['points'] = 'Cost to redeem (Points Required) is required.';
        } elseif (!filter_var($pointsRaw, FILTER_VALIDATE_INT) || (int)$pointsRaw < 1) {
            $errors['points'] = 'Points must be a positive integer greater than 0.';
        } else {
            $data['points'] = (int)$pointsRaw;
        }

        // 4. Card Monetary Value validation
        $cardValueRaw = trim($input['cardValue'] ?? '');
        if ($cardValueRaw === '') {
            $errors['cardValue'] = 'Card Value is required.';
        } elseif (!is_numeric($cardValueRaw) || (float)$cardValueRaw <= 0) {
            $errors['cardValue'] = 'Card Value must be a valid dollar amount greater than $0.00.';
        } else {
            $data['cardValue'] = (float)$cardValueRaw;
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

