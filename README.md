
## General

```shell
git checkout tags/Step1
./run-step-1.sh
# wait for a minute dependencies would install
# follow Step1.md
./run-step-1.sh down

git checkout tags/Step2
./run-step-2.sh
# wait for a minute dependencies would install
# follow Step2.md
./run-step-2.sh down

git checkout tags/Step3
./run-step-3.sh
# wait for a minute dependencies would install
# follow Step3.md
./run-step-3.sh down

git checkout tags/Step4
./run-step-4.sh
# wait for a minute dependencies would install
# follow Step4.md
./run-step-4.sh down

git checkout tags/Step5
./run-step-5.sh
# wait for a minute dependencies would install
# follow Step5.md
./run-step-5.sh down
```

## Changes

I think the patient.onHold is private property and shouldn't be exposed (to admins only). That's why it was hidden.

## Production

TODO:

* add some healthcheck in both Dockerfiles

Set .env variables: `MAIN_TAG`.

```shell
docker compose -f docker-compose.build.yml build
```
