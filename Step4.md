# Step 4

Up:

```shell
./run-step-4.sh
```

Down:

```shell
./run-step-4.sh down
```

## Api server

Go to http://localhost:3000/graphql and try those queries:


```graphql
query GetPatientById {
  patientById(id: 1) {
    success
    message
    data {
      id
      title
      firstName
      lastName
      dob
      createdAt
      updatedAt
    }
  }
}

```
