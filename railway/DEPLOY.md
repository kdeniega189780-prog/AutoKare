# Railway deployment

## Required variables

Set these on the Laravel service in the Railway dashboard before deploy. Use [`.env.railway`](../.env.railway) as a copy-paste template.

| Variable | Notes |
|----------|--------|
| `APP_URL` | Exact public HTTPS URL (e.g. `https://your-service.up.railway.app`). Wrong values break sessions/CSRF after `config:cache`. |
| `APP_KEY` | Stable value from `php artisan key:generate --show`. Do not rotate without invalidating sessions. |
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `SESSION_DRIVER` | `database` (requires MySQL and migrated `sessions` table) |
| `LOG_CHANNEL` | `stderr` |
| MySQL refs | `MYSQL_*` variables from a linked MySQL service (see `.env.railway`) |

Leave `SESSION_DOMAIN` unset unless you use a custom domain.

## Deploy flow

1. Link MySQL to the app service.
2. Set variables above.
3. Deploy — `railway/init-app.sh` runs `migrate --force`, then `config:cache`.
4. Health check: `GET /up`.

## UI assets

Authenticated pages load AdminLTE from `/vehicle-ref/...` (relative paths). Static files live in `public/vehicle-ref/` in the repo. `npm run build` (Vite) is not used by Blade layouts.

## Verify after deploy

1. Log in — CSS loads from `https://<host>/vehicle-ref/dist/css/adminlte.min.css` (200).
2. Spot-check `/admin/users`, `/mechanic/tasks`, `/customer/vehicles`.
3. Logout — should redirect to `/login`, not 419.

## Troubleshooting

| Symptom | Check |
|---------|--------|
| Unstyled dashboards | Network tab: CSS host should match the app URL, not `localhost`. |
| Logout 419 | `APP_URL`, `APP_KEY`, MySQL sessions table, redeploy after env change. |
| DB errors | MySQL plugin linked; `railway/env-mysql.sh` maps `MYSQL_*` at runtime. |
