import express from "express";
import { ApolloServer } from "@apollo/server";
import { expressMiddleware } from "@apollo/server/express4";
import { gql } from "graphql-tag";
import cors from "cors";
import bodyParser from "body-parser";

const app = express();

// Define GraphQL Schema
const typeDefs = gql`
  type Query {
    hello: String
  }
`;

// Define Resolvers
const resolvers = {
  Query: {
    hello: (): string => "Hello World!"
  }
};

// Initialize Apollo Server
const server = new ApolloServer({ typeDefs, resolvers, csrfPrevention: false, introspection: true });

async function startServer() {
  await server.start();


  app.use(
    "/graphql",
    cors(),
    bodyParser.json(),
    // @ts-ignore
    expressMiddleware(server)
  );

  const PORT = process.env.PORT || 3000;

  app.listen(PORT, () => {
    console.log(`🚀 Server ready at http://localhost:${PORT}/graphql`);
    console.log(`🎭 Apollo Sandbox available at http://localhost:${PORT}/graphql`);
  });
}

startServer();
