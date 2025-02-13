# Step 1

Up:

```shell
./run-step-1.sh
```

Down:

```shell
./run-step-1.sh down
```

## Main server

```shell
curl -s -X GET http://localhost:8080/api/hello-world | grep 'Hello World!'
```

Or go to http://localhost:8080/api/doc



## Api server
```shell
curl -s -X POST http://localhost:3000/graphql \
  -H "Content-Type: application/json" \
  -H "x-apollo-operation-name: TestQuery" \
  -d '{"query": "{ hello }"}' | grep 'Hello World!'
```

Or go to http://localhost:3000/graphql
and in 'Operation' paste instead of default

```graphql
query ExampleQuery {
  hello
}
```

and click 'ExampleQuery' button