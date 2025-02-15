# Step 5

Up:

```shell
./run-step-5.sh
```

Down:

```shell
./run-step-5.sh down
```

## Main server

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

Will get response something like:

```json
{
  "id": 15,
  "title": "Mr",
  "firstName": "John",
  "lastName": "Doe",
  "createdAt": "2025-02-15T18:29:48+00:00",
  "updatedAt": "2025-02-15T18:29:48+00:00",
  "dob": "1990-05-15"
}
```

Remember this id (in the example above -- `15`).

## Api server

Go to http://localhost:3000/graphql and try those queries:


```graphql
mutation CreateBooking {
    createBooking(
        doctorName: "Dr Andrew Edison"
        date: "2025-02-15"
        hour: 10
        patientFullName: "Mr John Doe"
        comments: "Annual check-up"
    ) {
        success
        message
        data {
            id
            doctorName
            date
            hour
            patientFullName
            comments
        }
    }
}
```

Then run (use according id -- in current example it's `15`):

```graphql
query GetPatientById {
  patientById(id: 15) {
    success
    message
    data {
      id
      title
      firstName
      lastName
      dob
      bookings(start: "2025-02-15", end: "2025-03-01") {
        id
        date
        hour
        doctorName
      }
      createdAt
      updatedAt
    }
  }
}

```
