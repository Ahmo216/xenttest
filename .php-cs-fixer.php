<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

// terminal commands:
// Get a complete description of one single rule:
// 'php php-cs-fixer describe <rule_name>'
// Execute a 'dry run' on any file or directory to see what changes this ruleset will make:
// 'php php-cs-fixer fix <file_or_directory_path> --allow-risky=yes --show-progress dots --dry-run  --diff --diff-format=udiff --verbose'
$rules = [
    // Code must be PSR-2 compliant.
    '@PSR2' => true,
    // There must be a blank line after the '<?php' tag
    'blank_line_after_opening_tag' => true,
    // Every line of a doc-block must start with an asterisk (`*`).
    'align_multiline_comment' => ['comment_type' => 'phpdocs_only'],
    // Multi-line arrays must be indented.
    'array_indentation' => true,
    // The short array syntax must be used (e.g. `$array = [1,2];`).
    'array_syntax' => ['syntax' => 'short'],
    // Rules for (binary) operators:
    'binary_operator_spaces' => [
        'operators' => [
            '=>' => 'single_space',
        ]
    ],
    // Every `return` statement must be preceded by a blank line.
    'blank_line_before_statement' => ['statements' => ['return']],
    // Rules for braces:
    'braces' => [
        // Opening and closing braces on the same line is generally allowed.
        'allow_single_line_closure' => true,
        // Opening braces of classes and functions must be on a new line.
        'position_after_functions_and_oop_constructs' => 'next',
        /// Opening braces of control structures (if, for each, switch etc.) must be on the same line.
        'position_after_control_structures' => 'same',
        // Opening braces of anonymous functions and closures must be on the same line.
        'position_after_anonymous_constructs' => 'same',
    ],
    // There must be no space between a type-cast and the variable name (e.g. `(int)$foo`).
    'cast_spaces' => ['space' => 'none'],
    // Type-cast instructions must be lowercase.
    'lowercase_cast' => true,
    // Attributes of classes must be separated from prior ones by a blank line.
    'class_attributes_separation' => [
        'elements' => [
            'method' => 'one',
            'const' => 'one',
            'property' => 'one',
        ]
    ],
    // Nullable typehints must have no space in between (e.g. `?string`).
    'compact_nullable_typehint' => true,
    // There must be a space between every part of a string concatenation.
    'concat_space' => ['spacing' => 'one'],
    // There must be no space around an `=` operator in a `declare` statement (e.g. `declare(strict_types=1);`)
    'declare_equal_normalize' => ['space' => 'none'],
    // Typehints for objects must use the classname instead of the fully qualified class name.
    'fully_qualified_strict_types' => true,
    // There must be **one** space between typehint and variable name in argument declarations.
    'function_typehint_space' => true,
    // There must be one space after every argument.
    'method_argument_space' => [
        'on_multiline' => 'ensure_fully_multiline',
        'keep_multiple_spaces_after_comma' => false
    ],
    // `include` and `require` statements must not use braces.
    'include' => true,
    // `list()` notation for list assignments is mandatory.
    'list_syntax' => ['syntax' => 'long'],
    // Static references must be lowercase e.g. `self::foo()` or `static::foo()`.
    'lowercase_static_reference' => true,
    // Magic constants must be in correct case.
    'magic_constant_casing' => true,
    // Magic methods must be in correct case.
    'magic_method_casing' => true,
    // PHP native functions must be in correct case.
    'native_function_casing' => true,
    // Type declarations in PHP native functions must be in correct case.
    'native_function_type_declaration_casing' => true,
    // `new`-statements must be preceded by braces (e.g. `$foo = new Foo()`).
    'new_with_braces' => true,
    // Control structures must use braces.
    'no_alternative_syntax' => true,
    // There must not be an empty line between a class declaration and the opening brace.
    'no_blank_lines_after_class_opening' => true,
    // There must not be an empty line between a doc-block and the preceding code.
    'no_blank_lines_after_phpdoc' => true,
    // There must not be empty doc-blocks.
    'no_empty_phpdoc' => true,
    // There must not be superfluous semicolons.
    'no_empty_statement' => true,
    // Rules for superfluous empty lines:
    'no_extra_blank_lines' => [
        'tokens' => [
            'extra',     // There must not be superfluous empty lines between statements.
            'throw',     // There must not be empty lines after a `throw` statement.
            'use',       // There must not be empty lines between `use` statements.
            'use_trait', // There must not be empty lines between `use` statements for traits.
            'return',    // There must not be empty lines after a `return` statement.
            'continue',  // There must not be empty lines after a `continue` statement.
        ]
    ],
    // Paths in `use` statements must not start with a `\`.
    'no_leading_import_slash' => true,
    // There must be no empty space at the beginning of a line before the `namespace` declaration.
    'no_leading_namespace_whitespace' => true,
    // `echo` must be used instead of `print`.
    'no_mixed_echo_print' => ['use' => 'echo'],
    // Constructor methods must be called `__construct()`. PHP4 style constructors are not allowed.
    'no_php4_constructor' => true,
    // Short cast `bool` using double exclamation mark is not allowed.
    'no_short_bool_cast' => true,
    // Short syntax for `echo` is not allowed.
    'echo_tag_syntax' => true,
    // There must not be spaces before the semicolon at the end of a line.
    'no_singleline_whitespace_before_semicolons' => true,
    // There must not be spaces around offset braces.
    'no_spaces_around_offset' => ['positions' => ['inside', 'outside']],
    // There must not be a trailing comma in a `list` call.
    'no_trailing_comma_in_list_call' => true,
    // There must not be trailing comma in a single-line array.
    'no_trailing_comma_in_singleline_array' => true,
    // There must not be superfluous parentheses in the code.
    'no_unneeded_control_parentheses' => true,
    // There must not be superfluous curly braces in the code.
    'no_unneeded_curly_braces' => ['namespaces' => true],
    // There must not be unused imports.
    'no_unused_imports' => true,
    // There must not be a space before a comma in an array.
    'no_whitespace_before_comma_in_array' => true,
    // There must not be spaces in a blank line.
    'no_whitespace_in_blank_line' => true,
    // Curly braces notation for array access is not allowed.
    'normalize_index_brace' => true,
    // There must not be space around a member access operator e.g. `$object->method()`.
    'object_operator_without_whitespace' => true,
    // Imports must be sorted alphabetically
    'ordered_imports' => true,
    // Use camelCase in method names of PHPUnit tests.
    'php_unit_method_casing' => true,
    // Add all arguments of a function in the doc-block as `@param` annotation.
    'phpdoc_add_missing_param_annotation' => true,
    // Align all doc-block elements vertical.
    'phpdoc_align' => ['align' => 'vertical'],
    // Doc-blocks must have the same indentation as the code they belong to.
    'phpdoc_indent' => true,
    // Rules for doc-blocks:
    'phpdoc_line_span' => [
        'const' => 'single',
        'property' => 'single',
        'method' => 'multi',
    ],
    // There must not be any `@access` annotations in doc-blocks.
    'phpdoc_no_access' => true,
    // There must be a `@return void` annotation for void methods.
    'phpdoc_no_empty_return' => false,
    // There must not ba a `@package` annotation in a class doc-block.
    'phpdoc_no_package' => true,
    // Classes and methods that are not inherited must not have a `@inheritdoc` annotation in the doc-block.
    'phpdoc_no_useless_inheritdoc' => true,
    // Annotations in doc-blocks must be ordered `@param` then `@throws` then `@return`.
    'phpdoc_order' => true,
    // Use scalar typehints in doc-blocks.
    'phpdoc_scalar' => true,
    // Annotations in a doc-block must be grouped. Groups must be seperated by an empty line.
    'phpdoc_separation' => true,
    // Doc-blocks for variables and properties must be a single line doc-block.
    'phpdoc_single_line_var_spacing' => true,
    // Doc-blocks must not start or end with an empty line excluding the very first and last line of the doc-blocks.
    'phpdoc_trim' => true,
    // There must not be more than one blank line after summary and after description in a doc-block.
    'phpdoc_trim_consecutive_blank_line_separation' => true,
    // Type annotations in doc-block must use the same case as the standard PHP types.
    'phpdoc_types' => true,
    // Order several typehints in the doc-block.
    'phpdoc_types_order' => [
        'null_adjustment' => 'always_last',
        'sort_algorithm' => 'none',
    ],
    // Typehint in doc-block must precede the variable name.
    'phpdoc_var_annotation_correct_order' => true,
    // Doc-block `@param` annotations must contain the variable name.
    'phpdoc_var_without_name' => false,
    // Class names must be PSR-4 compliant (file name and classname must be equal).
    'psr_autoloading' => true,
    // Only **one** `use`-trait statement is allowed per line.
    'single_trait_insert_per_statement' => true,
    // There must be spaces around ternary operators (e.g. `$a = $a ? 1 : 0;`).
    'ternary_operator_spaces' => true,
    // Trailing comma in multiline-array is mandatory.
    'trailing_comma_in_multiline' => true,
    // There must be a white space after every array element.
    'whitespace_after_comma_in_array' => true,
    // There must not be more than one space after every array element.
    'trim_array_spaces' => true,
    // Unary operators must be placed adjacent to their operands.
    'unary_operator_spaces' => true,
    // All class members must have visibility declarations.
    'visibility_required' => [
        'elements' => [
            'property',
            'method',
            'const',
        ]
    ],
    // Method chaining must be indented.
    'method_chaining_indentation' => true,
    // There must not be empty comments
    'no_empty_comment' => true,
    // There must not be surrounding multiline-whitespaces around double arrow.
    'no_multiline_whitespace_around_double_arrow' => true,
    // There must not be a space between function name and following brace.
    'no_spaces_after_function_name' => true,
    // There must not be empty else cases
    'no_useless_else' => true,
    // There must not be useless empty `return` statement.
    'no_useless_return' => true,
    // There must be no space before colon in return type declaration and there must be one spacer after it.
    'return_type_declaration' => ['space_before' => 'none'],
    // Use single quote where possible.
    'single_quote' => true,
    // Variables inside string must use explicit syntax (e.g. `$str = "Variable: {$value}";`)
    'explicit_string_variable' => true,
    // Nullabe arguments must have `?` Operator in addition to default `null` value.
    'nullable_type_declaration_for_default_null_value' => ['use_nullable_type_declaration' => true],
    // All class members must be ordered by:
    //  1. `use` trait statements
    //  2. `const` expressions (`public` > `protected` > `private)
    //  3. properties (`public` > `protected` > `private)
    //  4. constructor and destructor
    //  5. magic methods
    //  6. `public` methods
    //  7. methods (`protected` > `private`)
    'ordered_class_elements' => [
        'order' => [
            'use_trait',
            'constant_public',
            'constant_protected',
            'constant_private',
            'property_public',
            'property_protected',
            'property_private',
            'construct',
            'destruct',
            'magic',
            'phpunit',
            'method_public',
            'method_protected',
            'method_private',
        ],
        'sort_algorithm' => 'none', // Members of the same type are not ordered alphabetical.
    ],
    // `setUp` and `tearDown` methods must have visibility `protected`
    //'php_unit_set_up_tear_down_visibility' => true,
    // There must be no `@expectedException` annotation in PHPUnit tests.
    //'php_unit_no_expectation_annotation' => true,
    // Instead of `setExpectedException` use `expectException` (necessary to fix the outcome of the prior rule).
    //'php_unit_expectation' => true,
    // There must be a `declare(strict_types=true);` at the beginning of each php file.
    //'declare_strict_types' => true,
    // `@covers` annotations must be in alphabetic order.
    //'php_unit_ordered_covers' => true,
    // Rules for static method calls e.g. `assertSame()`:
    //'php_unit_test_case_static_method_calls' => ['call_type' => 'static'],

    // link: https://mlocati.github.io/php-cs-fixer-configurator/#version:2.16|fixer:no_superfluous_phpdoc_tags
    // 2020-10-23: development pitch: we decided to leave this out for now.
    // 'no_superfluous_phpdoc_tags' => true,
];

$finder = Finder::create()
    ->in(['app', 'tests']);

return (new Config())
    ->setRules($rules)
    ->setFinder($finder);
