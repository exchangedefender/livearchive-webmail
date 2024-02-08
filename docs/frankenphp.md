LiveArchive is powered by [frankenphp](https://frankenphp.dev) which uses [caddy](https://caddyserver.com) under the hood which provides endless flexibility to how your web server runs.

# Recipes

## Enabling SSL

frankenphp uses the environment variable `SERVER_NAME` to use with Caddy's [address binding](https://caddyserver.com/docs/caddyfile/concepts#addresses)

for example, if you owned the domain `foo.bar` and wanted to run livearchive using the hostname `livearchive.foo.bar` you could run livearchive by editing your docker compose file and adding the environment key `SERVER_NAME: livearchive.foo.bar` to enable HTTPS and attempt to obtain a certificate from letsencrypt using Caddy's [automatic https](https://caddyserver.com/docs/automatic-https#acme-challenges)


## Enabling basic auth

- https://frankenphp.dev/docs/config/
- https://caddyserver.com/docs/caddyfile/directives/basicauth
- https://caddyserver.com/docs/caddyfile/concepts

frankenphp includes extra caddy directives into the config via the environment variable `CADDY_SERVER_EXTRA_DIRECTIVES`. We can use this to simply add directives like basic auth to the Caddyfile without needing to recompile

1) create a hashed password
`docker compose livearchive exec frankenphp hash-password`
2) format the directive to inject as `"basicauth * {\n$YOUR_USERNAME_HERE $YOUR_ENCODED_PASSWORD_HERE\n}"` 
3) edit `docker-compose.yml` and add an environment key variable for `CADDY_SERVER_EXTRA_DIRECTIVES`

for example, to have the username `admin` and password `livearchive` you would add 

```yaml
environment:
  CADDY_SERVER_EXTRA_DIRECTIVES: "basicauth * {\nadmin $2a$14$/iEcYdGh55HAdL9X2ZqWVuQ8R1b9GyeiqDx2Iq/F5OjJ3.M5.nQf6\n}"
```

