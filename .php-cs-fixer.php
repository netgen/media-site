<?php

declare(strict_types=1);

return new PhpCsFixer\Config()
    ->setRiskyAllowed(true)
    ->setRules([
        '@PER-CS' => true,
        '@PER-CS:risky' => true,
        '@PhpCsFixer' => true,
        '@PhpCsFixer:risky' => true,

        // Overrides for rules included in PhpCsFixer rule sets
        'concat_space' => ['spacing' => 'one'],
        'fully_qualified_strict_types' => ['phpdoc_tags' => []],
        'method_chaining_indentation' => false,
        'multiline_promoted_properties' => true,
        'multiline_whitespace_before_semicolons' => false,
        'native_function_invocation' => ['include' => ['@all']],
        'no_superfluous_phpdoc_tags' => false,
        'no_unset_on_property' => false,
        'operator_linebreak' => false,
        'ordered_imports' => ['imports_order' => ['class', 'function', 'const'], 'sort_algorithm' => 'alpha'],
        'ordered_types' => ['null_adjustment' => 'always_last', 'sort_algorithm' => 'none'],
        'php_unit_data_provider_method_order' => false,
        'php_unit_data_provider_name' => false,
        'php_unit_internal_class' => false,
        'php_unit_test_case_static_method_calls' => false,
        'php_unit_test_class_requires_covers' => false,
        'phpdoc_align' => false,
        'phpdoc_order' => ['order' => ['param', 'throws', 'return']],
        'phpdoc_types_order' => ['null_adjustment' => 'always_last', 'sort_algorithm' => 'none'],
        'single_line_comment_style' => false,
        'trailing_comma_in_multiline' => ['elements' => ['arrays', 'arguments', 'match', 'parameters']],
        'yoda_style' => ['equal' => false, 'identical' => false, 'less_and_greater' => false],

        // Additional rules
        'date_time_immutable' => true,
        'declare_strict_types' => true,
        'global_namespace_import' => [
            'import_classes' => null,
            'import_constants' => true,
            'import_functions' => true,
        ],
        'heredoc_indentation' => ['indentation' => 'same_as_start'],
        'mb_str_functions' => true,
        'native_constant_invocation' => true,
        'nullable_type_declaration_for_default_null_value' => true,
        'static_lambda' => true,
        'ternary_to_null_coalescing' => true,
        'use_arrow_functions' => true,
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(['src', 'tests']),
    )
;
