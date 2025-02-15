import tseslint from "@typescript-eslint/eslint-plugin";
import tsparser from "@typescript-eslint/parser";

export default {
  ignores: ["node_modules/", "dist/"],

  files: ["src/**/*.ts"],

  languageOptions: {
    parser: tsparser,
    parserOptions: {
      project: "tsconfig.json",
      tsconfigRootDir: process.cwd(),
      sourceType: "module",
      ecmaVersion: "latest",
    },
  },

  plugins: {
    "@typescript-eslint": tseslint,
  },

  rules: {
    "@typescript-eslint/no-explicit-any": "off",
  },
};
