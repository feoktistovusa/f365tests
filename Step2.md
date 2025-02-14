# Step 2

Up:

```shell
./run-step-2.sh
```

Down:

```shell
./run-step-2.sh down
```

## Main server

```shell
curl -s -X GET http://localhost:8080/api/patients?page=1&limit=10&dob=
```

Set `dob` or leave empty.

To query one item, if know id:

```shell
curl -s -X GET http://localhost:8080/api/patient/{id}
```

To query one item in resulted array, if know dob only:

```shell
curl -s -X GET http://localhost:8080/api/patients?page=1&limit=1&dob=
```

Set according `dob`.

To create patient:

```shell
curl -s -X 'POST' \
  'http://localhost:8080/api/patients' \
  -H 'accept: */*' \
  -H 'Content-Type: application/json' \
  -d '{
  "title": "Mr",
  "firstName": "John",
  "lastName": "Doe",
  "dob": "1990-05-15"
}'
```

To update patient (for example, `title`):

```shell
curl -X 'PATCH' \
  'http://localhost:8080/api/patients/1' \
  -H 'accept: */*' \
  -H 'Content-Type: application/json' \
  -d '{
  "title": "Dr"
}'
```

Other fields (separately or together) could be updated accordingly.

To delete patient:

```shell
curl -X 'DELETE' \
  'http://localhost:8080/api/patients/1' \
  -H 'accept: */*'
```


Or go to http://localhost:8080/api/doc -- Swagger and its UI.
