<?php

declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    die('This script supports command line usage only. Please check your command.');
}

return (new \PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules(
        [
            '@DoctrineAnnotation' => true,
            '@PHP56Migration:risky' => true,
            '@PHP70Migration' => true,
            '@PHP70Migration:risky' => true,
            '@PHPUnit57Migration:risky' => true,
            '@PHPUnit60Migration:risky' => true,
            '@PHPUnit75Migration:risky' => true,
            '@PSR2' => true,
            'array_syntax' => ['syntax' => 'short'],
            'cast_spaces' => ['space' => 'none'],
            'class_attributes_separation' => true,
            'combine_consecutive_issets' => true,
            'combine_consecutive_unsets' => true,
            'compact_nullable_typehint' => true,
            'concat_space' => ['spacing' => 'one'],
            'declare_equal_normalize' => true,
            'declare_strict_types' => true,
            'dir_constant' => true,
            'echo_tag_syntax' => true,
            'elseif' => true,
            'encoding' => true,
            'escape_implicit_backslashes' => ['single_quoted' => true],
            'function_typehint_space' => true,
            'is_null' => true,
            'linebreak_after_opening_tag' => true,
            'lowercase_cast' => true,
            'magic_constant_casing' => true,
            'modernize_types_casting' => true,
            'multiline_whitespace_before_semicolons' => true,
            'native_function_casing' => true,
            'new_with_braces' => true,
            'no_alias_functions' => true,
            'no_blank_lines_after_class_opening' => true,
            'no_blank_lines_after_phpdoc' => true,
            'no_empty_comment' => true,
            'no_empty_phpdoc' => true,
            'no_empty_statement' => true,
            'no_extra_blank_lines' => true,
            'no_leading_import_slash' => true,
            'no_leading_namespace_whitespace' => true,
            'no_php4_constructor' => true,
            'no_short_bool_cast' => true,
            'no_singleline_whitespace_before_semicolons' => true,
            'no_spaces_after_function_name' => true,
            'no_spaces_inside_parenthesis' => true,
            'no_superfluous_elseif' => true,
            'no_trailing_comma_in_singleline_array' => true,
            'no_unneeded_control_parentheses' => true,
            'no_unneeded_curly_braces' => true,
            'no_unused_imports' => true,
            'no_useless_else' => true,
            'no_useless_return' => true,
            'no_whitespace_before_comma_in_array' => true,
            'no_whitespace_in_blank_line' => true,
            'ordered_imports' => true,
            'php_unit_construct' => true,
            'php_unit_dedicate_assert' => true,
            'php_unit_expectation' => true,
            'php_unit_fqcn_annotation' => true,
            'php_unit_method_casing' => true,
            'php_unit_mock' => true,
            'php_unit_mock_short_will_return' => true,
            'php_unit_no_expectation_annotation' => true,
            'php_unit_set_up_tear_down_visibility' => true,
            'php_unit_test_annotation' => ['style' => 'annotation'],
            'php_unit_test_case_static_method_calls' => ['call_type' => 'self'],
            'phpdoc_add_missing_param_annotation' => true,
            'phpdoc_indent' => true,
            'phpdoc_no_package' => true,
            'phpdoc_order_by_value' => true,
            'phpdoc_scalar' => true,
            'phpdoc_separation' => true,
            'phpdoc_trim' => true,
            'phpdoc_types' => true,
            'phpdoc_types_order' => ['null_adjustment' => 'always_last', 'sort_algorithm' => 'none'],
            'psr_autoloading' => true,
            'return_type_declaration' => ['space_before' => 'none'],
            'semicolon_after_instruction' => true,
            'short_scalar_cast' => true,
            'single_line_comment_style' => true,
            'single_quote' => true,
            'space_after_semicolon' => true,
            'standardize_not_equals' => true,
            'ternary_operator_spaces' => true,
            'ternary_to_null_coalescing' => true,
            'trailing_comma_in_multiline' => true,
            'unary_operator_spaces' => true,
            'whitespace_after_comma_in_array' => true,
        ]
    );
