module.exports = {
  extends: [
    // Add more generic rulesets here.
    'eslint:recommended',
    'plugin:vue/recommended'
  ],
  rules: {
    "vue/max-attributes-per-line": ["error", {
      "singleline": 3,
      "multiline": {
        "max": 3,
        "allowFirstLine": false
      },
      // Allow long SVG paths (i.e. <path d="M47.2388, 12.45..."/>) to exceed the maximum line length.
      "ignorePattern": 'd="([\\s\\S]*?)"',
    }]
  }
}
