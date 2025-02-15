
## Changes

I think the patient.onHold is private property and shouldn't be exposed (to admins only). That's why it was hidden.

## Production

TODO:

* add some healthcheck in both Dockerfiles

Set .env variables: `MAIN_TAG`.

```shell
docker compose -f docker-compose.build.yml build
```
