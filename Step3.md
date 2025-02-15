# Step 3

Up:

```shell
./run-step-3.sh
```

Down:

```shell
./run-step-3.sh down
```

## Api server

Go to http://localhost:3000/graphql and try those queries:


```graphql
mutation CreateBooking {
    createBooking(
        doctorName: "Dr. John Doe"
        date: "2025-02-15"
        hour: 10
        patientFullName: "Charlie Brown"
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

```graphql
query GetBookingById {
    bookingById(id: 1) {
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

```graphql
query GetBookingsByDate {
    bookingsByDate(date: "2025-02-15") {
        success
        message
        data {
            id
            doctorName
            hour
            patientFullName
            comments
        }
    }
}
```

```graphql
mutation UpdateBooking {
    updateBooking(
        id: 3
        doctorName: "Dr. Jane Smith"
        comments: "Rescheduled appointment"
    ) {
        success
        message
        data {
            id
            doctorName
            comments
        }
    }
}
```

```graphql
mutation DeleteBooking {
    deleteBooking(id: 3) {
        success
        message
    }
}
```
