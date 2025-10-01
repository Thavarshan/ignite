# Contributing to Ignite

Thanks for taking the time to contribute! The following guidelines help us keep the project healthy and maintainable.

## Project Workflow

1. **Create an issue** – Use the issue templates so we can discuss changes before you start coding.
2. **Fork and branch** – Work from a feature branch based on `main` (for example, `feature/thread-search`).
3. **Stay in sync** – Rebase frequently so your branch stays up to date with `main`.
4. **Write tests** – Every change should be covered by feature or unit tests. Run `php artisan test` before sending a pull request.
5. **Lint and format** – Run `composer lint:php`, `npm run lint`, and `npm run format:check`. The pre-commit hook will run these automatically, but running them manually helps catch issues sooner.
6. **Open a pull request** – Fill out the PR template and reference related issues using `Fixes #123`.

## Commit Style

- Keep commits focused; avoid mixing unrelated changes.
- Use present tense, imperative style commit messages (e.g., `Add channel filter to thread index`).

## Coding Standards

- **PHP** – Follow Laravel and Pint conventions. Type declarations are required.
- **Vue/TypeScript** – Follow ESLint rules defined in `eslint.config.js`. Use the existing component patterns before adding new ones.
- **Styling** – Use Tailwind utility classes and prefer existing design tokens/components.

## Development Tips

- `npm run dev` runs the Vite dev server.
- `npm run build` prepares assets for production.
- `npm run lint:staged` mirrors the pre-commit hook if you need to troubleshoot.

## Reporting Security Issues

Do not create a public issue. Follow the steps in [SECURITY.md](SECURITY.md).

We appreciate your help keeping Ignite growing and improving!
